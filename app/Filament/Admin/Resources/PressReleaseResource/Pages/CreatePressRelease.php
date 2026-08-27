<?php

namespace App\Filament\Admin\Resources\PressReleaseResource\Pages;

use App\Filament\Admin\Resources\PressReleaseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePressRelease extends CreateRecord
{
    protected static string $resource = PressReleaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
