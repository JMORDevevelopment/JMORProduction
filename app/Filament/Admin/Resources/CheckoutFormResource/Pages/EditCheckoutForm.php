<?php

namespace App\Filament\Admin\Resources\CheckoutFormResource\Pages;

use App\Filament\Admin\Resources\CheckoutFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCheckoutForm extends EditRecord
{
    protected static string $resource = CheckoutFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function ($record): void {
                    $record->formFields()->delete();
                    $record->systemFields()->delete();
                }),
        ];
    }
}
