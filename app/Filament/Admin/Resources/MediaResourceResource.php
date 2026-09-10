<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaResourceResource\Pages\CreateMediaResource;
use App\Filament\Admin\Resources\MediaResourceResource\Pages\EditMediaResource;
use App\Filament\Admin\Resources\MediaResourceResource\Pages\ListMediaResources;
use App\Models\MediaResource;
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

class MediaResourceResource extends Resource
{
    protected static ?string $model = MediaResource::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Media Resources';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Media Resource';

    protected static ?string $pluralModelLabel = 'Media Resources';

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
                    ->unique(ignoreRecord: true)
                    ->dehydrated(),

                Textarea::make('description')
                    ->label('Content')
                    ->rows(10),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->directory('uploads/media-resources')
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
            'index' => ListMediaResources::route('/'),
            'create' => CreateMediaResource::route('/create'),
            'edit' => EditMediaResource::route('/{record}/edit'),
        ];
    }
}
