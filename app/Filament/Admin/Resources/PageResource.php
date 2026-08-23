<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PageResource\Pages\CreatePage;
use App\Filament\Admin\Resources\PageResource\Pages\EditPage;
use App\Filament\Admin\Resources\PageResource\Pages\ListPages;
use App\Models\Menu;
use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pages';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Page';

    protected static ?string $pluralModelLabel = 'Pages';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('link', Str::slug($state));
                    }),

                Select::make('menu_location')
                    ->label('Parent Menu')
                    ->options(function () {
                        return Menu::where('parent_id', 0)
                            ->orderBy('position')
                            ->pluck('title', 'id')
                            ->prepend('None (No Parent)', 0);
                    })
                    ->default(0),

                Toggle::make('menu_status')
                    ->label('Show in Menu')
                    ->default(false),

                Toggle::make('show_in_sitemap')
                    ->label('Show in Sitemap')
                    ->default(true),

                Toggle::make('slider_status')
                    ->label('Show Slider')
                    ->default(false),

                TextInput::make('priority')
                    ->label('Priority')
                    ->numeric()
                    ->default(0),

                Textarea::make('description')
                    ->label('Content')
                    ->rows(10),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->directory('uploads/pages')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),

                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(255),

                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->maxLength(255),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3),
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
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('link')
                    ->label('Slug')
                    ->limit(40),

                TextColumn::make('menu_status')
                    ->label('In Menu')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('show_in_sitemap')
                    ->label('Sitemap')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),
            ])
            ->reorderable('priority')
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
