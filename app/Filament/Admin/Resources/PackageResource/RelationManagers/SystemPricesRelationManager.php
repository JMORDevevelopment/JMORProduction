<?php

namespace App\Filament\Admin\Resources\PackageResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SystemPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'systemPrices';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('system_price')
                    ->label('Price')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->prefix('$'),

                TextInput::make('from_qty')
                    ->label('From Qty')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                TextInput::make('to_qty')
                    ->label('To Qty')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('system_price')
            ->columns([
                TextColumn::make('system_price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('from_qty')
                    ->label('From Qty')
                    ->sortable(),

                TextColumn::make('to_qty')
                    ->label('To Qty')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
