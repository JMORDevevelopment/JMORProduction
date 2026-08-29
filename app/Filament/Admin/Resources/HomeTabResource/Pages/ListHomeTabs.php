<?php

namespace App\Filament\Admin\Resources\HomeTabResource\Pages;

use App\Filament\Admin\Resources\HomeTabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeTabs extends ListRecords
{
    protected static string $resource = HomeTabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
