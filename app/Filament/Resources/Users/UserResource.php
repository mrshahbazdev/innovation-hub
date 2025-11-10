<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // v4 syntax
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// --- NAYE IMPORTS ---
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),
                FileUpload::make('profile_photo_path')
                    ->label('Profile Photo')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('profile-photos')
                    ->columnSpanFull(),
                Select::make('current_team_id')
                    ->label('Current Team')
                    ->relationship('currentTeam', 'name')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_admin')
                    ->required(),

                // --- 1. YEH NAYA ROLES DROPDOWN ADD HUA HAI ---
                Select::make('roles')
                    ->relationship('roles', 'name') // 'roles' function (jo HasRoles trait se aaya)
                    ->multiple() // User ke paas ek se zyada roles ho sakte hain
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('profile_photo_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('currentTeam.name')
                    ->label('Current Team')
                    ->sortable(),
                IconColumn::make('is_admin')
                    ->boolean(),

                // --- 2. YEH NAYA ROLES COLUMN ADD HUA HAI ---
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge() // Roles ko badges ki tarah dikhayega
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
