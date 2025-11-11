<?php

namespace App\Filament\Resources\Ideas;

use App\Filament\Resources\Ideas\Pages\ManageIdeas;
use App\Models\Idea;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IdeaResource extends Resource
{
    protected static ?string $model = Idea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'problem_short';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                TextInput::make('submitter_type')
                    ->required(),
                TextInput::make('contact_info'),
                TextInput::make('problem_short')
                    ->required(),
                Textarea::make('goal')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('problem_detail')
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'pending_review' => 'Pending Review',
                        'pending_pricing' => 'Pending Pricing',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('new'),

                TextInput::make('schmerz')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('prio_1')
                    ->numeric()
                    ->readOnly()
                    ->disabled(),
                TextInput::make('prio_2')
                    ->numeric()
                    ->readOnly()
                    ->disabled(),
                TextInput::make('umsetzung')
                    ->numeric(),
                Textarea::make('loesung')
                    ->columnSpanFull(),
                TextInput::make('kosten')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('dauer')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('problem_short')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('team.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('submitter_type')
                    ->searchable(),
                TextColumn::make('contact_info')
                    ->searchable(),
                TextColumn::make('problem_short')
                    ->label('Idea')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('schmerz')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prio_1')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prio_2')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('umsetzung')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kosten')
                    ->numeric()
                    ->money('usd')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dauer')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => ManageIdeas::route('/'),
        ];
    }
}
