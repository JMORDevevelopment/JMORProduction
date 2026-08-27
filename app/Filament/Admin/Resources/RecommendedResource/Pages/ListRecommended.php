<?php

namespace App\Filament\Admin\Resources\RecommendedResource\Pages;

use App\Filament\Admin\Resources\RecommendedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecommended extends ListRecords
{
    protected static string $resource = RecommendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
