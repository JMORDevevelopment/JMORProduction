<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactUsResource\Pages\CreateContactUs;
use App\Filament\Admin\Resources\ContactUsResource\Pages\EditContactUs;
use App\Filament\Admin\Resources\ContactUsResource\Pages\ListContactUs;
use App\Models\ContactUs;
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

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contact Us';

    protected static string|\UnitEnum|null $navigationGroup = 'Inquiries';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Contact Entry';

    protected static ?string $pluralModelLabel = 'Contact Entries';

    protected static ?string $slug = 'contact-us';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(80),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(50),

                TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(50),

                TextInput::make('reason')
                    ->label('Reason')
                    ->maxLength(100),

                Textarea::make('message')
                    ->label('Message')
                    ->rows(5),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'New',
                        0 => 'Preview',
                    ])
                    ->default(1)
                    ->required(),

                TextInput::make('ip')
                    ->label('IP')
                    ->maxLength(255),

                TextInput::make('date_time')
                    ->label('Submitted')
                    ->maxLength(50),
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
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => (string) $state === '1' ? 'New' : 'Preview')
                    ->color(fn ($state): string => (string) $state === '1' ? 'danger' : 'info'),

                TextColumn::make('date_time')
                    ->label('Submitted')
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
            'index' => ListContactUs::route('/'),
            'create' => CreateContactUs::route('/create'),
            'edit' => EditContactUs::route('/{record}/edit'),
        ];
    }
}
