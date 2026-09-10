<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SliderResource\Pages\CreateSlider;
use App\Filament\Admin\Resources\SliderResource\Pages\EditSlider;
use App\Filament\Admin\Resources\SliderResource\Pages\ListSliders;
use App\Models\Slider;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Sliders';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Slider';

    protected static ?string $pluralModelLabel = 'Sliders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slider_name')
                    ->label('Title')
                    ->required()
                    ->maxLength(100),

                Textarea::make('slider_desc')
                    ->label('Description')
                    ->rows(5),

                TextInput::make('slider_link')
                    ->label('Link URL')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('https://example.com/page'),

                FileUpload::make('slider_image')
                    ->label('Image')
                    ->directory('uploads/slider')
                    ->disk('public_direct')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),

                TextInput::make('priority')
                    ->label('Priority')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slider_id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('slider_name')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slider_link')
                    ->label('Link')
                    ->limit(50)
                    ->url(fn ($record) => $record->slider_link)
                    ->openUrlInNewTab(),

                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable(),
            ])
            ->defaultSort('priority', 'asc')
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
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}
