<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PressReleaseResource\Pages;
use App\Models\PressRelease;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PressReleaseResource extends Resource
{
    protected static ?string $model = PressRelease::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|UnitEnum|null $navigationGroup = 'Media Relations';

    protected static ?string $navigationLabel = 'Press Releases';

    protected static ?string $modelLabel = 'Press Release';

    protected static ?string $pluralModelLabel = 'Press Releases';

    protected static ?int $navigationSort = 2;

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
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'link',
                                Str::slug($state ?? '')
                            )),

                        Forms\Components\Textarea::make('description')
                            ->label('Content')
                            ->required()
                            ->rows(10)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('uploads/press_releases')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imagePreviewHeight('150')
                            ->required(fn (?string $operation): bool => $operation === 'create'),
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

                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->disk('public'),
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
            'index' => Pages\ListPressReleases::route('/'),
            'create' => Pages\CreatePressRelease::route('/create'),
            'edit' => Pages\EditPressRelease::route('/{record}/edit'),
        ];
    }
}
