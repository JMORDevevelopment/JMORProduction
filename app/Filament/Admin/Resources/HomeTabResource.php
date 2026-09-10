<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HomeTabResource\Pages\CreateHomeTab;
use App\Filament\Admin\Resources\HomeTabResource\Pages\EditHomeTab;
use App\Filament\Admin\Resources\HomeTabResource\Pages\ListHomeTabs;
use App\Models\HomeTab;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeTabResource extends Resource
{
    protected static ?string $model = HomeTab::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Home Tabs';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'Home Tab';

    protected static ?string $pluralModelLabel = 'Home Tabs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tab_title')
                    ->label('Tab Title')
                    ->required()
                    ->maxLength(100),

                Textarea::make('tab_description')
                    ->label('Tab Description')
                    ->rows(3),

                Repeater::make('tab_list')
                    ->label('Features List')
                    ->schema([
                        TextInput::make('item')
                            ->label('Feature')
                            ->required(),
                    ])
                    ->addActionLabel('Add Feature')
                    ->defaultItems(0)
                    ->columnSpanFull(),

                Repeater::make('benefits')
                    ->label('Benefits')
                    ->schema([
                        TextInput::make('item')
                            ->label('Benefit')
                            ->required(),
                    ])
                    ->addActionLabel('Add Benefit')
                    ->defaultItems(0)
                    ->columnSpanFull(),

                Repeater::make('cost')
                    ->label('Pricing')
                    ->schema([
                        TextInput::make('item')
                            ->label('Price')
                            ->required(),
                    ])
                    ->addActionLabel('Add Price')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tab_id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('tab_title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (HomeTab $record): string => collect([
                        is_array($record->tab_list) ? count(array_filter($record->tab_list)).' features' : null,
                        is_array($record->benefits) ? count(array_filter($record->benefits)).' benefits' : null,
                        is_array($record->cost) ? count(array_filter($record->cost)).' pricing' : null,
                    ])->filter()->implode(', ')),
            ])
            ->defaultSort('tab_id', 'asc')
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
            'index' => ListHomeTabs::route('/'),
            'create' => CreateHomeTab::route('/create'),
            'edit' => EditHomeTab::route('/{record}/edit'),
        ];
    }
}
