<?php

namespace App\Filament\Admin\Resources\RadioShowResource\Pages;

use App\Filament\Admin\Resources\RadioShowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRadioShows extends ListRecords
{
    protected static string $resource = RadioShowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
