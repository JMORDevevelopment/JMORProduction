<?php

namespace App\Filament\Admin\Resources\AdminUserResource\Pages;

use App\Filament\Admin\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $data['role'] = (int) ($data['role'] ?? 0);
        $data['status'] = (int) ($data['status'] ?? 0);

        return $data;
    }
}
