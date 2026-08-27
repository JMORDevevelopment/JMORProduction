<?php

namespace App\Filament\Admin\Resources\NewsResource\Pages;

use App\Filament\Admin\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
