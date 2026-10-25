<?php

namespace App\Filament\Admin\Resources\MemberRoleResource\Pages;

use App\Filament\Admin\Resources\MemberRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberRole extends EditRecord
{
    protected static string $resource = MemberRoleResource::class;

    protected ?array $selectedPermissions = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedPermissions = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncPermissions($this->selectedPermissions ?? []);
    }
}
