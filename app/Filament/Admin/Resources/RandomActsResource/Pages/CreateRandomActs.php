<?php

namespace App\Filament\Admin\Resources\RandomActsResource\Pages;

use App\Filament\Admin\Resources\RandomActsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateRandomActs extends CreateRecord
{
    protected static string $resource = RandomActsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
