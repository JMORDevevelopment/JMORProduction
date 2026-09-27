<?php

namespace App\Filament\Admin\Resources\RequestInformationResource\Pages;

use App\Filament\Admin\Resources\RequestInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestInformations extends ListRecords
{
    protected static string $resource = RequestInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
