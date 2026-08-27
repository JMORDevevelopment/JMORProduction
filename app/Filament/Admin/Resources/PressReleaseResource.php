<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PressReleaseResource\Pages\CreatePressRelease;
use App\Filament\Admin\Resources\PressReleaseResource\Pages\EditPressRelease;
use App\Filament\Admin\Resources\PressReleaseResource\Pages\ListPressReleases;
use App\Models\PressRelease;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PressReleaseResource extends Resource
{
    protected static ?string $model = PressRelease::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Press Releases';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Press Release';

    protected static ?string $pluralModelLabel = 'Press Releases';

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

                TextInput::make('link')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->dehydrated(),

                Textarea::make('description')
                    ->label('Content')
                    ->rows(10),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->directory('uploads/press-releases')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                DateTimePicker::make('published')
                    ->label('Published')
                    ->default(now()),

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

                TextColumn::make('published')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListPressReleases::route('/'),
            'create' => CreatePressRelease::route('/create'),
            'edit' => EditPressRelease::route('/{record}/edit'),
        ];
    }
}
