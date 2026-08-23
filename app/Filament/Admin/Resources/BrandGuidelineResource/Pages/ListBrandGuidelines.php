<?php

namespace App\Filament\Admin\Resources\BrandGuidelineResource\Pages;

use App\Filament\Admin\Resources\BrandGuidelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrandGuidelines extends ListRecords
{
    protected static string $resource = BrandGuidelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
