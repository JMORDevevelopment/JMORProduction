<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentOrdersWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Orders';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::orderByDesc('id')->limit(5)
            )
            ->columns([
                TextColumn::make('order_id')
                    ->label('Order ID')
                    ->formatStateUsing(fn ($state) => ($state ?? 0) + 10000),

                TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->limit(20),

                TextColumn::make('published')
                    ->label('Date')
                    ->formatStateUsing(fn ($state) => $state ? date('m-d-Y', strtotime($state)) : ''),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => '$'.number_format($state ?? 0, 2)),

                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        $order = Order::find($record->order_id);

                        return match ($order?->status) {
                            1 => 'Pending',
                            2 => 'Completed',
                            default => 'Unknown',
                        };
                    })
                    ->badge()
                    ->color(fn ($record) => match (Order::find($record->order_id)?->status) {
                        1 => 'danger',
                        2 => 'success',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
