<?php

namespace App\Filament\Admin\Resources\RequestInformationResource\Pages;

use App\Filament\Admin\Resources\RequestInformationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRequestInformation extends CreateRecord
{
    protected static string $resource = RequestInformationResource::class;

    /**
     * request_information.ip is NOT NULL without a default; the original
     * admin form omitted it and relied on non-strict MySQL inserting ''.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ip'] = $data['ip'] ?? '';

        return $data;
    }
}
