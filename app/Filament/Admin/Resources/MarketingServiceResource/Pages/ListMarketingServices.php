<?php

namespace App\Filament\Admin\Resources\MarketingServiceResource\Pages;

use App\Filament\Admin\Resources\MarketingServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingServices extends ListRecords
{
    protected static string $resource = MarketingServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
