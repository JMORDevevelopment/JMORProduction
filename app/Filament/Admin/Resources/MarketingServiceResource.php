<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MarketingServiceResource\Pages\CreateMarketingService;
use App\Filament\Admin\Resources\MarketingServiceResource\Pages\EditMarketingService;
use App\Filament\Admin\Resources\MarketingServiceResource\Pages\ListMarketingServices;
use App\Models\MarketingService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Marketing services shown as the checkbox list on the talk show guest
 * checkout (original CI: admin/Services — list / add / edit / delete on
 * the `services` table).
 */
class MarketingServiceResource extends Resource
{
    protected static ?string $model = MarketingService::class;

    protected static ?string $slug = 'marketing-services';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Marketing Services';

    protected static string|\UnitEnum|null $navigationGroup = 'Talk Show';

    protected static ?int $navigationSort = 28;

    protected static ?string $modelLabel = 'Marketing Service';

    protected static ?string $pluralModelLabel = 'Marketing Services';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_code')
                    ->label('Product Code')
                    ->required(),

                TextInput::make('name')
                    ->label('Name')
                    ->required(),

                TextInput::make('description')
                    ->label('Description')
                    ->required(),

                TextInput::make('question')
                    ->label('Question'),

                TextInput::make('price')
                    ->label('Price'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('product_code')
                    ->label('Product Code')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60),

                TextColumn::make('question')
                    ->label('Question')
                    ->limit(40),

                TextColumn::make('price')
                    ->label('Price'),
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
            'index' => ListMarketingServices::route('/'),
            'create' => CreateMarketingService::route('/create'),
            'edit' => EditMarketingService::route('/{record}/edit'),
        ];
    }
}
