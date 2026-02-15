<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductImageResource\Pages;
use App\Filament\Resources\ProductImageResource\RelationManagers;
use App\Models\ProductImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use App\Models\Product;


class ProductImageResource extends Resource
{
    protected static ?string $model = ProductImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Product Image';
    protected static ?string $navigationLabel = 'Product Image';



    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('product_id')
            ->label('product name')
            ->options(Product::pluck('title','id'))
            ->searchable()
            ->nullable()
            ,
            Forms\Components\fileUpload::make('image_url')->image()->directory('image_url'),
            Toggle::make('is_primary')->label('is_primary')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('product'),
                Tables\Columns\TextColumn::make('image_url')->label('image'),
                Tables\Columns\BooleanColumn::make('is_primary')->label('main image'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductImages::route('/'),
            'create' => Pages\CreateProductImage::route('/create'),
            'edit' => Pages\EditProductImage::route('/{record}/edit'),
        ];
    }
}
