<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RandomActsResource\Pages;
use App\Models\RandomActsOfKindness;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class RandomActsResource extends Resource
{
    protected static ?string $model = RandomActsOfKindness::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|UnitEnum|null $navigationGroup = 'Social';

    protected static ?string $navigationLabel = 'Random Acts';

    protected static ?string $modelLabel = 'Random Act of Kindness';

    protected static ?string $pluralModelLabel = 'Random Acts';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('link', Str::slug($state ?? ''))),

                        Forms\Components\TextInput::make('link')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('uploads/random_acts_of_kindness')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imagePreviewHeight('250'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('published')
                            ->label('Published')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
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
                    ->label('Image')
                    ->disk('public'),

                Tables\Columns\IconColumn::make('published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('published')
                    ->label('Published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRandomActs::route('/'),
            'create' => Pages\CreateRandomAct::route('/create'),
            'edit' => Pages\EditRandomAct::route('/{record}/edit'),
        ];
    }
}
