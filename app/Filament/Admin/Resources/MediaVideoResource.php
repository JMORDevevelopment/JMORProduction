<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MediaVideoResource\Pages;
use App\Models\MediaVideo;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class MediaVideoResource extends Resource
{
    protected static ?string $model = MediaVideo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-video-camera';

    protected static string|UnitEnum|null $navigationGroup = 'Media Relations';

    protected static ?string $navigationLabel = 'Media Videos';

    protected static ?string $modelLabel = 'Media Video';

    protected static ?string $pluralModelLabel = 'Media Videos';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set(
                                'link',
                                Str::slug($state ?? '')
                            )),

                        Forms\Components\Textarea::make('description')
                            ->label('Content')
                            ->required()
                            ->rows(10)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('video_link')
                            ->label('Video URL')
                            ->url()
                            ->maxLength(500),
                    ])
                    ->columns(2),

                Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keyword')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('video_link')
                    ->label('Video')
                    ->url()
                    ->limit(50),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaVideos::route('/'),
            'create' => Pages\CreateMediaVideo::route('/create'),
            'edit' => Pages\EditMediaVideo::route('/{record}/edit'),
        ];
    }
}
