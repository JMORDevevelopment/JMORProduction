<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TalkShowSettingResource\Pages\CreateTalkShowSetting;
use App\Filament\Admin\Resources\TalkShowSettingResource\Pages\EditTalkShowSetting;
use App\Filament\Admin\Resources\TalkShowSettingResource\Pages\ListTalkShowSettings;
use App\Models\TalkShowSetting;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Talk show checkout settings (price + application question).
 *
 * Original CI: admin/Talk_show_settings — a list with inline price/question
 * forms; only the first row (`TalkShowSetting::first()`) is used by the
 * guest checkout page.
 */
class TalkShowSettingResource extends Resource
{
    protected static ?string $model = TalkShowSetting::class;

    protected static ?string $slug = 'talk-show-settings';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Talk Show Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Talk Show';

    protected static ?int $navigationSort = 27;

    protected static ?string $modelLabel = 'Setting';

    protected static ?string $pluralModelLabel = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('price')
                    ->label('Price')
                    ->required(),

                TextInput::make('question')
                    ->label('Question')
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

                TextColumn::make('price')
                    ->label('Price')
                    ->searchable(),

                TextColumn::make('question')
                    ->label('Question')
                    ->searchable(),
            ])
            ->defaultSort('id', 'asc')
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTalkShowSettings::route('/'),
            'create' => CreateTalkShowSetting::route('/create'),
            'edit' => EditTalkShowSetting::route('/{record}/edit'),
        ];
    }
}
