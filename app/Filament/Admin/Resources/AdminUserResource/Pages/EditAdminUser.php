<?php

namespace App\Filament\Admin\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

    protected ?string $selectedRole = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $superAdmin = config('filament-shield.super_admin.name');

        $this->selectedRole = (string) ($data['role'] ?? $superAdmin);

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $data['role'] = $this->selectedRole === $superAdmin ? 1 : 2;
        $data['status'] = (int) ($data['status'] ?? 0);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles([
            $this->selectedRole ?? config('filament-shield.super_admin.name'),
        ]);
    }
}
