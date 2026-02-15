<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\{TextInput, Select, FileUpload, Grid};
use Filament\Tables\Columns\{TextColumn, ImageColumn, BadgeColumn};
use App\Filament\Resources\UserResource\Pages;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;



class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $modelLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('phone')
                    ->maxLength(20),
                TextInput::make('pincode')
                    ->maxLength(20),
                    TextInput::make('password')
                    ->password()
                    ->label('New Password')
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create')
                    ->maxLength(255),

                Select::make('user_type')
                    ->required()
                    ->options([
                        'user' => 'User',
                        'seller' => 'Seller',
                        'admin' => 'Admin',
                    ]),
                FileUpload::make('profile_image')
                    ->image()
                    ->directory('profile_images'),
            ]),
            TextInput::make('address')
                ->label('Address')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('profile_image')->label('Photo'),
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable()->sortable(),
            BadgeColumn::make('user_type')
                ->colors([
                    'primary' => 'user',
                    'success' => 'seller',
                    'danger' => 'admin',
                ]),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make()

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

