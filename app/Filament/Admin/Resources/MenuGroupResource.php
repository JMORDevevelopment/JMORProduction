<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuGroupResource\Pages\CreateMenuGroup;
use App\Filament\Admin\Resources\MenuGroupResource\Pages\EditMenuGroup;
use App\Filament\Admin\Resources\MenuGroupResource\Pages\ListMenuGroups;
use App\Models\Menu;
use App\Models\MenuGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuGroupResource extends Resource
{
    protected static ?string $model = MenuGroup::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Menu Groups';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Menu Group';

    protected static ?string $pluralModelLabel = 'Menu Groups';

    protected static ?string $slug = 'menu-groups';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('menus_count')
                    ->label('Menus')
                    ->counts('menus')
                    ->placeholder('0'),
            ])
            ->defaultSort('id', 'asc')
            ->actions([
                EditAction::make(),
                // CI blocks deleting group 1 and removes the group's menus.
                DeleteAction::make()
                    ->visible(fn (MenuGroup $record): bool => $record->getKey() !== 1)
                    ->after(function (MenuGroup $record): void {
                        Menu::where('group_id', $record->getKey())->delete();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuGroups::route('/'),
            'create' => CreateMenuGroup::route('/create'),
            'edit' => EditMenuGroup::route('/{record}/edit'),
        ];
    }
}
