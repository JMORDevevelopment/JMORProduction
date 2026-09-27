<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RequestInformationResource\Pages\CreateRequestInformation;
use App\Filament\Admin\Resources\RequestInformationResource\Pages\EditRequestInformation;
use App\Filament\Admin\Resources\RequestInformationResource\Pages\ListRequestInformations;
use App\Models\RequestInformation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequestInformationResource extends Resource
{
    protected static ?string $model = RequestInformation::class;

    protected static ?string $recordTitleAttribute = 'first_name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Request Information';

    protected static string|\UnitEnum|null $navigationGroup = 'Inquiries';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Request';

    protected static ?string $pluralModelLabel = 'Request Information';

    protected static ?string $slug = 'request-information';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('First Name')
                    ->maxLength(80),

                TextInput::make('last_name')
                    ->label('Last Name')
                    ->maxLength(80),

                TextInput::make('company')
                    ->label('Company')
                    ->maxLength(100),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(80),

                TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(50),

                TextInput::make('fax')
                    ->label('Fax'),

                TextInput::make('address')
                    ->label('Address'),

                TextInput::make('suite')
                    ->label('Suite'),

                TextInput::make('city')
                    ->label('City'),

                TextInput::make('state')
                    ->label('State')
                    ->maxLength(80),

                TextInput::make('zip')
                    ->label('Zip')
                    ->maxLength(80),

                TextInput::make('service_intersted')
                    ->label('Service Interested')
                    ->maxLength(100),

                Textarea::make('message')
                    ->label('Message')
                    ->rows(5),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'New',
                        1 => 'Preview',
                    ])
                    ->default(0)
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

                TextColumn::make('first_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn ($record): string => trim("{$record->first_name} {$record->last_name}")),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('service_intersted')
                    ->label('Service'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '0' ? 'New' : 'Preview')
                    ->color(fn ($state): string => (string) $state === '0' ? 'danger' : 'info'),
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
            'index' => ListRequestInformations::route('/'),
            'create' => CreateRequestInformation::route('/create'),
            'edit' => EditRequestInformation::route('/{record}/edit'),
        ];
    }
}
