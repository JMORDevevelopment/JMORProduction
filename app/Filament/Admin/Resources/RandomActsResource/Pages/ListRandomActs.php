<?php

namespace App\Filament\Admin\Resources\RandomActsResource\Pages;

use App\Filament\Admin\Resources\RandomActsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRandomActs extends ListRecords
{
    protected static string $resource = RandomActsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
