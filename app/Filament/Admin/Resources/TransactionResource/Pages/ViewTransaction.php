<?php

namespace App\Filament\Admin\Resources\TransactionResource\Pages;

use App\Filament\Admin\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateStatus')
                ->label('Update Status')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->authorize('updateStatus')
                ->form([
                    Select::make('status')
                        ->label('Order Status')
                        ->options([
                            1 => 'Pending',
                            2 => 'Completed',
                        ])
                        ->required()
                        ->default(fn (Transaction $record): int => $record->order?->status ?? 1),
                ])
                ->action(function (array $data, Transaction $record): void {
                    $order = $record->order;
                    if ($order) {
                        $order->update(['status' => $data['status']]);
                    }
                    Notification::make()
                        ->title('Status updated')
                        ->success()
                        ->send();
                })
                ->modalSubmitActionLabel('Save'),
            Actions\DeleteAction::make(),
        ];
    }
}
