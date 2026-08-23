<?php

namespace App\Filament\Admin\Resources\RecommendedResource\Pages;

use App\Filament\Admin\Resources\RecommendedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecommended extends EditRecord
{
    protected static string $resource = RecommendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
