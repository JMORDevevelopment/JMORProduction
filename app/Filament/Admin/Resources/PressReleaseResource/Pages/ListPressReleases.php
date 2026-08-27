<?php

namespace App\Filament\Admin\Resources\PressReleaseResource\Pages;

use App\Filament\Admin\Resources\PressReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPressReleases extends ListRecords
{
    protected static string $resource = PressReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
