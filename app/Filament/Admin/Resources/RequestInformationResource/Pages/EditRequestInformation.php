<?php

namespace App\Filament\Admin\Resources\RequestInformationResource\Pages;

use App\Filament\Admin\Resources\RequestInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestInformation extends EditRecord
{
    protected static string $resource = RequestInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
