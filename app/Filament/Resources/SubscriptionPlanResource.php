<?php
namespace App\Filament\Resources;

use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Filament\Resources\SubscriptionPlanResource\Pages;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Subscription Plan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('price')->numeric()->required(),

            Select::make('duration_type')
                ->options([
                    'daily' => 'Daily',
                    'weekly' => 'Weekly',
                    'monthly' => 'Monthly',
                    'yearly' => 'Yearly',
                ])
                ->required(),

            TextInput::make('duration_value')->numeric()->required(),

            TextInput::make('max_listings')->numeric()->required(),
            TextInput::make('max_images_per_listing')->numeric()->required(),

            Toggle::make('featured_listings')->label('Featured Listing'),
            Toggle::make('is_active')->label('Active'),

            Textarea::make('description')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            TextColumn::make('price')->money('USD'),
            TextColumn::make('duration_type')->label('Type'),
            TextColumn::make('duration_value')->label('Value'),
            TextColumn::make('max_listings'),
            TextColumn::make('max_images_per_listing'),
            IconColumn::make('featured_listings')->boolean(),
            IconColumn::make('is_active')->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}

