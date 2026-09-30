<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LanguageResource\Pages\CreateLanguage;
use App\Filament\Admin\Resources\LanguageResource\Pages\EditLanguage;
use App\Filament\Admin\Resources\LanguageResource\Pages\ListLanguages;
use App\Models\Language;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static ?string $navigationLabel = 'Languages';

    protected static string|\UnitEnum|null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Language';

    protected static ?string $pluralModelLabel = 'Languages';

    protected static ?string $slug = 'languages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('language_id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('language_id', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLanguages::route('/'),
            'create' => CreateLanguage::route('/create'),
            'edit' => EditLanguage::route('/{record}/edit'),
        ];
    }
}
