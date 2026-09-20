<?php

namespace App\Filament\Admin\Resources\GiftCardTransactionResource\Pages;

use App\Filament\Admin\Resources\GiftCardTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListGiftCardTransactions extends ListRecords
{
    protected static string $resource = GiftCardTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
