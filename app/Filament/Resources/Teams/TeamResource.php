<?php

namespace App\Filament\Resources\Teams;

use App\Filament\Resources\Teams\Pages\ManageTeams;
use App\Models\Team;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Toggle; // Iski zaroorat nahi
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// --- 1. YEH IMPORTS ADD KAREIN ---
use Filament\Forms\Components\Hidden;
use Filament\Actions\CreateAction; // "New Team" button ke liye

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Team Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(), // Poori width le

                // Yeh fields ab chhupe (hidden) hain aur automatically set honge
                Hidden::make('user_id')
                    ->default(auth()->id()) // Current admin ko owner banaye
                    ->required(),

                Hidden::make('personal_team')
                    ->default(false) // Yeh personal team nahi hai
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('owner.name') // 'owner' relationship ka istemal
                    ->label('Owner')
                    ->sortable(),

                IconColumn::make('personal_team')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true), // Default mein chupa ho

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // --- 2. ACTIONS COLUMN YAHAN SE HATA DIYA GAYA HAI ---
            ])
            ->filters([
                //
            ])
            // --- 3. recordActions WAPAS ADD KAR DIYA GAYA HAI ---
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(), // "New Team" button
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTeams::route('/'),
            // create aur edit pages ki zaroorat nahi, modal istemal honge
        ];
    }
}
