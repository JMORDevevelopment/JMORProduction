<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Admin\Resources\TransactionResource\Pages\ViewTransaction;
use App\Models\Transaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Orders';

    protected static ?string $navigationLabel = 'Package Orders';

    protected static ?string $modelLabel = 'Package Order';

    protected static ?string $pluralModelLabel = 'Package Orders';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('order_type', '!=', 'Gift Card')
            ->with(['order', 'user'])
            ->orderByDesc('id');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Transaction Details')
                    ->schema([
                        TextInput::make('order_id')
                            ->label('Order ID')
                            ->disabled(),
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->disabled(),
                        TextInput::make('checkout_type')
                            ->label('Checkout Type')
                            ->disabled(),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->disabled(),
                        TextInput::make('auth_code')
                            ->label('Auth Code')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Display ID')
                    ->formatStateUsing(fn (Transaction $record): string => strval($record->id + 10000))
                    ->sortable(),
                TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable(),
                TextColumn::make('user.full_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('published')
                    ->label('Date')
                    ->date('m-d-Y')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('order.status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        1 => 'Pending',
                        2 => 'Completed',
                        default => 'Unknown',
                    })
                    ->badge()
                    ->color(fn ($state): string => match ((int) $state) {
                        1 => 'danger',
                        2 => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Invoice')
                    ->schema([
                        TextEntry::make('order_id')
                            ->label('Invoice #'),
                        TextEntry::make('published')
                            ->label('Date')
                            ->date('m-d-Y'),
                        TextEntry::make('user.full_name')
                            ->label('Customer Name'),
                    ])->columns(3),

                Section::make('Billing Info')
                    ->schema([
                        TextEntry::make('user.address')
                            ->label('Address'),
                        TextEntry::make('user.city')
                            ->label('City'),
                        TextEntry::make('user.state')
                            ->label('State'),
                        TextEntry::make('user.zip')
                            ->label('ZIP'),
                    ])->columns(4),

                Section::make('Payment Info')
                    ->schema([
                        TextEntry::make('checkout_type')
                            ->label('Type')
                            ->formatStateUsing(fn ($state): string => match ($state) {
                                'Monthly' => 'Transaction',
                                'Yearly' => 'Subscription',
                                default => $state,
                            }),
                        TextEntry::make('transaction_id')
                            ->label('Transaction #'),
                        TextEntry::make('published')
                            ->label('Date')
                            ->date('Y-m-d'),
                        TextEntry::make('order.status')
                            ->label('Status')
                            ->formatStateUsing(fn ($state): string => match ((int) $state) {
                                1 => 'Pending',
                                2 => 'Completed',
                                default => 'Unknown',
                            })
                            ->badge()
                            ->color(fn ($state): string => match ((int) $state) {
                                1 => 'danger',
                                2 => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('auth_code')
                            ->label('Auth Code'),
                    ])->columns(3),

                Section::make('Order Details')
                    ->schema([
                        TextEntry::make('order_id')
                            ->label('')
                            ->formatStateUsing(function ($state, Transaction $record): string {
                                $details = $record->order?->orderDetails;
                                if (! $details || $details->isEmpty()) {
                                    return 'No order details found.';
                                }

                                return collect($details)->map(
                                    fn ($d) => $d->item.' ('.$d->type.') x '.$d->qty.' = $'.number_format($d->sub_total, 2)
                                )->implode("\n");
                            })
                            ->columnSpanFull(),
                        TextEntry::make('order.sub_total')
                            ->label('Sub Total')
                            ->formatStateUsing(fn ($state) => '$'.number_format($state ?? 0, 2)),
                        TextEntry::make('order.discount')
                            ->label('Discount')
                            ->formatStateUsing(fn ($state) => '$'.number_format($state ?? 0, 2)),
                        TextEntry::make('order.grand_total')
                            ->label('Grand Total')
                            ->formatStateUsing(fn ($state) => '$'.number_format($state ?? 0, 2))
                            ->html(),
                    ])->columns(3),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'view' => ViewTransaction::route('/{record}'),
        ];
    }
}
