<?php

namespace App\Filament\Admin\Resources\MemberRoleResource\Pages;

use App\Filament\Admin\Resources\MemberRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberRole extends CreateRecord
{
    protected static string $resource = MemberRoleResource::class;

    protected ?array $selectedPermissions = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->selectedPermissions = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);

        $data['guard_name'] = 'web';

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncPermissions($this->selectedPermissions ?? []);
    }
}
