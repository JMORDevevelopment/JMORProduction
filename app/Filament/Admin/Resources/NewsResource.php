<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsResource\Pages;
use App\Models\News;
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

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|UnitEnum|null $navigationGroup = 'Social';

    protected static ?string $navigationLabel = 'News';

    protected static ?string $modelLabel = 'News Article';

    protected static ?string $pluralModelLabel = 'News Articles';

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
                            ->afterStateUpdated(fn ($set, ?string $state) => $set(
                                'link',
                                Str::slug($state ?? '')
                            )),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('uploads/news')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imagePreviewHeight('150')
                            ->required(fn (?string $operation): bool => $operation === 'create'),
                    ])
                    ->columns(2),

                Section::make('Details')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'general' => 'General',
                                'technology' => 'Technology',
                                'business' => 'Business',
                            ])
                            ->default('general'),

                        Forms\Components\TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('news_id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->disk('public'),
            ])
            ->defaultSort('news_id', 'desc')
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
