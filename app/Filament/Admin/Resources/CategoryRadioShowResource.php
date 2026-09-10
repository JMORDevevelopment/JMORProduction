<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryRadioShowResource\Pages\CreateCategoryRadioShow;
use App\Filament\Admin\Resources\CategoryRadioShowResource\Pages\EditCategoryRadioShow;
use App\Filament\Admin\Resources\CategoryRadioShowResource\Pages\ListCategoryRadioShows;
use App\Models\CategoryRadioShow;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryRadioShowResource extends Resource
{
    protected static ?string $model = CategoryRadioShow::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Radio Show Categories';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Radio Show Category';

    protected static ?string $pluralModelLabel = 'Radio Show Categories';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('link', Str::slug($state));
                    }),

                TextInput::make('sub_title')
                    ->label('Subtitle')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(5),

                TextInput::make('link')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrated(),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->preload()
                    ->placeholder('None (Top Level)'),

                Toggle::make('menu_status')
                    ->label('Show in Menu')
                    ->default(true),

                FileUpload::make('image')
                    ->label('Image')
                    ->directory('uploads/category_radio_show')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                DateTimePicker::make('published')
                    ->label('Published')
                    ->default(now()),
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

                TextColumn::make('link')
                    ->label('Slug')
                    ->limit(40),

                TextColumn::make('parent.title')
                    ->label('Parent')
                    ->sortable(),

                IconColumn::make('menu_status')
                    ->label('In Menu')
                    ->boolean(),

                TextColumn::make('published')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('menu_status')
                    ->label('Menu Status')
                    ->options([
                        1 => 'Shown',
                        0 => 'Hidden',
                    ]),
            ])
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
            'index' => ListCategoryRadioShows::route('/'),
            'create' => CreateCategoryRadioShow::route('/create'),
            'edit' => EditCategoryRadioShow::route('/{record}/edit'),
        ];
    }
}
