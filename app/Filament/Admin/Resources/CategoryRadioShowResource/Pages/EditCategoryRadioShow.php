<?php

namespace App\Filament\Admin\Resources\CategoryRadioShowResource\Pages;

use App\Filament\Admin\Resources\CategoryRadioShowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryRadioShow extends EditRecord
{
    protected static string $resource = CategoryRadioShowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
