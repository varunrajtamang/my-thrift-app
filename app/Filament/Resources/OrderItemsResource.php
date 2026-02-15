<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemsResource\Pages;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class OrderItemsResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $label = 'Order Item';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->options(Order::pluck('id', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('seller_id')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->options(Product::pluck('title','id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('price_per_unit')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('subtotal')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'returned' => 'Returned',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.id')->label('Order ID'),
                Tables\Columns\TextColumn::make('seller.name')->label('Seller'),
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('price_per_unit')->money('usd'),
                Tables\Columns\TextColumn::make('subtotal')->money('usd'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can register relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItems::route('/create'),
            'edit' => Pages\EditOrderItems::route('/{record}/edit'),
        ];
    }
}
