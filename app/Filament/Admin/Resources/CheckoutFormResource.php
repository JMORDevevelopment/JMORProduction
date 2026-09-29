<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CheckoutFormResource\Pages\CreateCheckoutForm;
use App\Filament\Admin\Resources\CheckoutFormResource\Pages\EditCheckoutForm;
use App\Filament\Admin\Resources\CheckoutFormResource\Pages\ListCheckoutForms;
use App\Models\CheckoutMeta;
use App\Models\Package;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CheckoutFormResource extends Resource
{
    protected static ?string $model = CheckoutMeta::class;

    protected static ?string $recordTitleAttribute = 'form_name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Checkout Forms';

    protected static string|\UnitEnum|null $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Checkout Form';

    protected static ?string $pluralModelLabel = 'Checkout Forms';

    protected static ?string $slug = 'checkout-forms';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('form_name')
                    ->label('Name')
                    ->required()
                    ->maxLength(100),

                Select::make('package_id')
                    ->label('Package')
                    ->options(fn (): array => Package::orderBy('name')->pluck('name', 'id')->all())
                    ->required(),

                Repeater::make('formFields')
                    ->label('Customer Fields')
                    ->relationship()
                    ->schema([
                        TextInput::make('label')
                            ->label('Label')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(100),

                        Select::make('types')
                            ->label('Type')
                            ->options([
                                1 => 'Input',
                                2 => 'Description Box',
                            ])
                            ->required(),

                        Select::make('required')
                            ->label('Required')
                            ->options([
                                1 => 'Required',
                                2 => 'No Required',
                            ])
                            ->required(),

                        TextInput::make('placeholder')
                            ->label('Placeholder')
                            ->maxLength(100),
                    ])
                    ->defaultItems(1)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),

                Repeater::make('systemFields')
                    ->label('System Information Fields')
                    ->relationship()
                    ->schema([
                        TextInput::make('s_label')
                            ->label('Label')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('s_name')
                            ->label('Name')
                            ->required()
                            ->maxLength(100),

                        Select::make('s_types')
                            ->label('Type')
                            ->options([
                                1 => 'Input',
                                2 => 'Description Box',
                            ])
                            ->required(),

                        Select::make('s_required')
                            ->label('Required')
                            ->options([
                                1 => 'Required',
                                2 => 'No Required',
                            ])
                            ->required(),

                        TextInput::make('s_placeholder')
                            ->label('Placeholder')
                            ->maxLength(100),
                    ])
                    ->defaultItems(1)
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['s_label'] ?? null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('form_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('package.name')
                    ->label('Package Name')
                    ->placeholder('—'),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function (CheckoutMeta $record): void {
                        $record->formFields()->delete();
                        $record->systemFields()->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function ($records): void {
                            foreach ($records as $record) {
                                $record->formFields()->delete();
                                $record->systemFields()->delete();
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCheckoutForms::route('/'),
            'create' => CreateCheckoutForm::route('/create'),
            'edit' => EditCheckoutForm::route('/{record}/edit'),
        ];
    }
}
