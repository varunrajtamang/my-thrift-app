<?php

namespace App\Filament\Resources;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\CartItemResource\Pages;

class CartItemResource extends Resource
{
    protected static ?string $model = CartItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Carts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('cart_id')
                ->relationship('cart', 'id')
                ->required(),

                Select::make('product_id')
                ->options(Product::pluck('title','id'))
                ->searchable()
                ->required(),

            DateTimePicker::make('added_at')
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('cart.id')->label('Cart ID')->sortable(),
            TextColumn::make('product.title')->label('Product'),
            TextColumn::make('quantity')->sortable(),
            TextColumn::make('added_at')->label('Added At')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCartItems::route('/'),
            'create' => Pages\CreateCartItem::route('/create'),
            'edit' => Pages\EditCartItem::route('/{record}/edit'),
        ];
    }
}
