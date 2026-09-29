<?php

namespace App\Filament\Admin\Resources\CheckoutFormResource\Pages;

use App\Filament\Admin\Resources\CheckoutFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCheckoutForms extends ListRecords
{
    protected static string $resource = CheckoutFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
