<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerSubscriptionResource\Pages;
use App\Filament\Resources\SellerSubscriptionResource\RelationManagers;
use App\Models\SellerSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;

class SellerSubscriptionResource extends Resource
{
    protected static ?string $model = SellerSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Subscription Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->relationship('user','name')->required(),


                Select::make('plan_id')
                ->relationship('plan','name')
                ->required(),

                DatePicker::make('start_date')
                ->required(),

                DatePicker::make('end_date')
                ->required(),

                Toggle::make('is_auto_renew')
                ->required(),

                Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                ])
                ->required(),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('user id')->searchable(),
                TextColumn::make('plan.name')->label('Plan Name')->searchable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('is_auto_renew')->badge(),
                TextColumn::make('payment_status')->badge()
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
            'index' => Pages\ListSellerSubscriptions::route('/'),
            'create' => Pages\CreateSellerSubscription::route('/create'),
            'edit' => Pages\EditSellerSubscription::route('/{record}/edit'),
        ];
    }
}
