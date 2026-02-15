<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralModelLabel = 'Orders';
    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form->schema([


            Forms\Components\Select::make('buyer_id')
                ->label('Buyer')
                ->options(fn () => User::pluck('name', 'id'))
                ->searchable()
                ->required(),




            DatePicker::make('order_date')
                ->required(),

            TextInput::make('total_amount')
                ->numeric()
                ->required(),

            Textarea::make('shipping_address')
                ->required(),

            Textarea::make('billing_address')
                ->required(),

            Select::make('payment_method')
                ->options([
                    'credit_card' => 'Credit Card',
                    'paypal' => 'PayPal',
                    'bank_transfer' => 'Bank Transfer',
                    'cash_on_delivery' => 'Cash on Delivery',
                ])
                ->required(),

            Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                ])
                ->required(),

            Select::make('order_status')
                ->options([
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

            TextInput::make('tracking_number')
                ->nullable(),

            Textarea::make('notes')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('buyer.name')->label('Buyer')->searchable(),
            Tables\Columns\TextColumn::make('order_date')->date()->sortable(),
            Tables\Columns\TextColumn::make('total_amount')->money('USD'),
            Tables\Columns\TextColumn::make('payment_method')->label('Payment'),
            Tables\Columns\TextColumn::make('payment_status')->badge(),
            Tables\Columns\TextColumn::make('order_status')->badge(),
            Tables\Columns\TextColumn::make('tracking_number'),
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
            // Add relation managers like OrderItems if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
