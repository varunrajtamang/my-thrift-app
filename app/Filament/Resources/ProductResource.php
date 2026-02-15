<?php

namespace App\Filament\Resources;



use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductCondition;
use App\Models\Size;
use App\Models\Color;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $pluralModelLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),
            Textarea::make('description')->required(),

            Select::make('seller_id')
            ->label('Seller')
            ->options(User::where('user_type', 'seller')->pluck('name', 'id'))
            ->searchable()
            ->required(),




            Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),

            Select::make('condition_id')
                ->relationship('condition', 'name')
                ->label('Condition')
                ->required(),

            Select::make('size_id')
                ->relationship('size', 'name')
                ->required(),

            Select::make('color_id')
                ->relationship('color', 'name')
                ->required(),

            TextInput::make('price')->numeric()->required(),
            TextInput::make('original_price')->numeric(),

            TextInput::make('brand'),
            TextInput::make('quantity')->numeric()->required(),

            Toggle::make('is_featured')->label('Featured?'),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'sold' => 'Sold',
                    'inactive' => 'Inactive',
                    'deleted' => 'Deleted',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->sortable(),
            TextColumn::make('seller.name')->label('Seller'),
            TextColumn::make('category.name')->label('Category'),
            TextColumn::make('price')->money('inr')->sortable(),
            BooleanColumn::make('is_featured'),
            BadgeColumn::make('status')->colors([
                'success' => 'active',
                'warning' => 'inactive',
                'danger' => 'deleted',
                'gray' => 'sold',
            ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\ViewAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
