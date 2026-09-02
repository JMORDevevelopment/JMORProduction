<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RecommendedResource\Pages\CreateRecommended;
use App\Filament\Admin\Resources\RecommendedResource\Pages\EditRecommended;
use App\Filament\Admin\Resources\RecommendedResource\Pages\ListRecommended;
use App\Models\Recommended;
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

class RecommendedResource extends Resource
{
    protected static ?string $model = Recommended::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Recommended';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Recommended';

    protected static ?string $pluralModelLabel = 'Recommended';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->trim()
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
                    ->rows(10)
                    ->trim(),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->directory('uploads/recommended')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),

                DateTimePicker::make('published')
                    ->label('Published')
                    ->required()
                    ->default(now()),

                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(255)
                    ->trim()
                    ->required(fn (string $operation): bool => $operation === 'create'),

                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->maxLength(255)
                    ->trim()
                    ->required(fn (string $operation): bool => $operation === 'create'),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->trim()
                    ->required(fn (string $operation): bool => $operation === 'create'),
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
            'index' => ListRecommended::route('/'),
            'create' => CreateRecommended::route('/create'),
            'edit' => EditRecommended::route('/{record}/edit'),
        ];
    }
}
