<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RecordingSessionResource\Pages\CreateRecordingSession;
use App\Filament\Admin\Resources\RecordingSessionResource\Pages\EditRecordingSession;
use App\Filament\Admin\Resources\RecordingSessionResource\Pages\ListRecordingSessions;
use App\Models\RecordingSession;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Recording sessions calendar entries.
 *
 * Original CI: admin/Recording_sessions — list + add form on the
 * `events_calendar` table (add/edit/accept there were copy-paste broken;
 * edit now targets the session itself).
 */
class RecordingSessionResource extends Resource
{
    protected static ?string $model = RecordingSession::class;

    protected static ?string $slug = 'recording-sessions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Recording Sessions';

    protected static string|\UnitEnum|null $navigationGroup = 'Talk Show';

    protected static ?int $navigationSort = 29;

    protected static ?string $modelLabel = 'Recording Session';

    protected static ?string $pluralModelLabel = 'Recording Sessions';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3),

                DatePicker::make('date_time')
                    ->label('Date')
                    ->required(),

                TextInput::make('link')
                    ->label('Link'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_time')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60),

                TextColumn::make('link')
                    ->label('Link')
                    ->limit(40),
            ])
            ->defaultSort('date_time', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecordingSessions::route('/'),
            'create' => CreateRecordingSession::route('/create'),
            'edit' => EditRecordingSession::route('/{record}/edit'),
        ];
    }
}
