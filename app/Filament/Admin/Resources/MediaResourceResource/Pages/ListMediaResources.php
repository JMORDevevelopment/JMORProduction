<?php

namespace App\Filament\Admin\Resources\MediaResourceResource\Pages;

use App\Filament\Admin\Resources\MediaResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaResources extends ListRecords
{
    protected static string $resource = MediaResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
