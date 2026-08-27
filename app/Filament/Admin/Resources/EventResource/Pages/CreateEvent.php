<?php

namespace App\Filament\Admin\Resources\EventResource\Pages;

use App\Filament\Admin\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
