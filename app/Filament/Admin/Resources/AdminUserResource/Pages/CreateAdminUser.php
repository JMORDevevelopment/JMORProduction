<?php

namespace App\Filament\Admin\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Resources\AdminUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['image'] = $data['image'] ?? '';
        $data['date_register'] = $data['date_register'] ?? now();
        $data['last_login'] = $data['last_login'] ?? now();
        $data['role'] = (int) ($data['role'] ?? 1);
        $data['status'] = (int) ($data['status'] ?? 1);

        return $data;
    }
}
