<?php

namespace App\Filament\Admin\Resources\BrandGuidelineResource\Pages;

use App\Filament\Admin\Resources\BrandGuidelineResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBrandGuideline extends CreateRecord
{
    protected static string $resource = BrandGuidelineResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
