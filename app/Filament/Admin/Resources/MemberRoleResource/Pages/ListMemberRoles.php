<?php

namespace App\Filament\Admin\Resources\MemberRoleResource\Pages;

use App\Filament\Admin\Resources\MemberRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemberRoles extends ListRecords
{
    protected static string $resource = MemberRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
