<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected ?string $selectedRole = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedRole = (string) ($data['role'] ?? 'member');
        unset($data['role']);

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $data['user_group_id'] = (int) ($data['user_group_id'] ?? 0);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles([
            $this->selectedRole ?? 'member',
        ]);
    }
}
