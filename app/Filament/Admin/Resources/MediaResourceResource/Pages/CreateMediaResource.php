<?php

namespace App\Filament\Admin\Resources\MediaResourceResource\Pages;

use App\Filament\Admin\Resources\MediaResourceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMediaResource extends CreateRecord
{
    protected static string $resource = MediaResourceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
