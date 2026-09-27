<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_group_id'] = (int) ($data['user_group_id'] ?? 1);
        $data['date_added'] = $data['date_added'] ?? date('Y-m-d');

        return $data;
    }
}
