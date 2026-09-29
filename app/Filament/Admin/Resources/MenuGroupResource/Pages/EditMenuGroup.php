<?php

namespace App\Filament\Admin\Resources\MenuGroupResource\Pages;

use App\Filament\Admin\Resources\MenuGroupResource;
use App\Models\Menu;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuGroup extends EditRecord
{
    protected static string $resource = MenuGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn ($record): bool => $record->getKey() !== 1)
                ->after(function ($record): void {
                    Menu::where('group_id', $record->getKey())->delete();
                }),
        ];
    }
}
