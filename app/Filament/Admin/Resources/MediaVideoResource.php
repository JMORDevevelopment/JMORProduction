<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaVideoResource\Pages\CreateMediaVideo;
use App\Filament\Admin\Resources\MediaVideoResource\Pages\EditMediaVideo;
use App\Filament\Admin\Resources\MediaVideoResource\Pages\ListMediaVideos;
use App\Models\MediaVideo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MediaVideoResource extends Resource
{
    protected static ?string $model = MediaVideo::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?string $navigationLabel = 'Media Videos';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Media Video';

    protected static ?string $pluralModelLabel = 'Media Videos';

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
                    ->dehydrated(),

                Textarea::make('description')
                    ->label('Content')
                    ->rows(10),

                TextInput::make('video_link')
                    ->label('Video URL')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('https://youtube.com/watch?v=...'),

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

                TextColumn::make('link')
                    ->label('Slug')
                    ->limit(40),

                TextColumn::make('video_link')
                    ->label('Video URL')
                    ->limit(50)
                    ->url(fn ($record) => $record->video_link)
                    ->openUrlInNewTab(),

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
            'index' => ListMediaVideos::route('/'),
            'create' => CreateMediaVideo::route('/create'),
            'edit' => EditMediaVideo::route('/{record}/edit'),
        ];
    }
}
