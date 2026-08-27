<?php

namespace App\Filament\Admin\Resources\RecommendedResource\Pages;

use App\Filament\Admin\Resources\RecommendedResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateRecommended extends CreateRecord
{
    protected static string $resource = RecommendedResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
