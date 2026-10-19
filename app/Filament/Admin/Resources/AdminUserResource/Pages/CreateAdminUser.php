<?php

namespace App\Filament\Admin\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Resources\AdminUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;

    protected ?string $selectedRole = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $superAdmin = config('filament-shield.super_admin.name');

        $this->selectedRole = (string) ($data['role'] ?? $superAdmin);

        $data['image'] = $data['image'] ?? '';
        $data['date_register'] = $data['date_register'] ?? now();
        $data['last_login'] = $data['last_login'] ?? now();
        $data['role'] = $this->selectedRole === $superAdmin ? 1 : 2;
        $data['status'] = (int) ($data['status'] ?? 1);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncRoles([
            $this->selectedRole ?? config('filament-shield.super_admin.name'),
        ]);
    }
}
