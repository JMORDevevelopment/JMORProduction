<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RadioShowResource\Pages\CreateRadioShow;
use App\Filament\Admin\Resources\RadioShowResource\Pages\EditRadioShow;
use App\Filament\Admin\Resources\RadioShowResource\Pages\ListRadioShows;
use App\Models\RadioShow;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RadioShowResource extends Resource
{
    protected static ?string $model = RadioShow::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-radio';

    protected static ?string $navigationLabel = 'Radio Shows';

    protected static string|\UnitEnum|null $navigationGroup = 'Media';

    protected static ?int $navigationSort = 21;

    protected static ?string $modelLabel = 'Radio Show';

    protected static ?string $pluralModelLabel = 'Radio Shows';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('link', Str::slug($state));
                    }),

                TextInput::make('link')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->dehydrated(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(10),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('show_date')
                    ->label('Show Date'),

                FileUpload::make('image')
                    ->label('Image')
                    ->directory('uploads/radio_show')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                DateTimePicker::make('published')
                    ->label('Published')
                    ->default(now()),

                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->required(),

                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->required(),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->required(),
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

                TextColumn::make('category.title')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('show_date')
                    ->label('Show Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('published')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'title'),
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
            'index' => ListRadioShows::route('/'),
            'create' => CreateRadioShow::route('/create'),
            'edit' => EditRadioShow::route('/{record}/edit'),
        ];
    }
}
