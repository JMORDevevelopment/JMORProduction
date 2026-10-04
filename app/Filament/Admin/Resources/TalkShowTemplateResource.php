<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalkShowTemplateResource\Pages\CreateTalkShowTemplate;
use App\Filament\Admin\Resources\TalkShowTemplateResource\Pages\EditTalkShowTemplate;
use App\Filament\Admin\Resources\TalkShowTemplateResource\Pages\ListTalkShowTemplates;
use App\Models\TalkShowTemplate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Revision-email templates (original CI: Talk_show_guests::templates —
 * list / add / delete, content loaded into the revision popup via AJAX).
 */
class TalkShowTemplateResource extends Resource
{
    protected static ?string $model = TalkShowTemplate::class;

    protected static ?string $slug = 'talk-show-templates';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Talk Show Templates';

    protected static string|\UnitEnum|null $navigationGroup = 'Talk Show';

    protected static ?int $navigationSort = 26;

    protected static ?string $modelLabel = 'Template';

    protected static ?string $pluralModelLabel = 'Templates';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                Textarea::make('content')
                    ->label('Content')
                    ->required()
                    ->rows(12),
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
                    ->searchable(),

                TextColumn::make('content')
                    ->label('Content')
                    ->limit(80),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => ListTalkShowTemplates::route('/'),
            'create' => CreateTalkShowTemplate::route('/create'),
            'edit' => EditTalkShowTemplate::route('/{record}/edit'),
        ];
    }
}
