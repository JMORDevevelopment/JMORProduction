<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuResource\Pages\CreateMenu;
use App\Filament\Admin\Resources\MenuResource\Pages\EditMenu;
use App\Filament\Admin\Resources\MenuResource\Pages\ListMenus;
use App\Models\Menu;
use App\Models\MenuGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Menu';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Menu Item';

    protected static ?string $pluralModelLabel = 'Menu Items';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->trim()
                    ->unique(ignoreRecord: true),

                TextInput::make('url')
                    ->label('URL')
                    ->maxLength(255)
                    ->trim()
                    ->rules(['nullable', 'url']),

                Select::make('parent_id')
                    ->label('Parent Menu')
                    ->options(function () {
                        return Menu::where('parent_id', 0)
                            ->orderBy('position')
                            ->pluck('title', 'id')
                            ->prepend('None (Top Level)', 0);
                    })
                    ->default(0)
                    ->required()
                    ->numeric(),

                Select::make('group_id')
                    ->label('Group')
                    ->options(fn () => MenuGroup::pluck('title', 'id'))
                    ->default(1)
                    ->required()
                    ->numeric(),

                Select::make('menu_type')
                    ->label('Menu Type')
                    ->options([
                        '' => 'None',
                        'Pages' => 'Pages',
                        'Jmor shows' => 'Jmor Shows',
                        'Packages' => 'Packages',
                    ])
                    ->default(''),

                TextInput::make('position')
                    ->label('Position')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),

                TextInput::make('page_id')
                    ->label('Page ID')
                    ->numeric()
                    ->default(0)
                    ->hidden(),
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

                TextColumn::make('url')
                    ->label('URL')
                    ->limit(50),

                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->sortable(),

                TextColumn::make('group.title')
                    ->label('Group')
                    ->sortable(),

                TextColumn::make('menu_type')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('position')
                    ->label('Position')
                    ->sortable(),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position')
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
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
