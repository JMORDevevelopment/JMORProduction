<?php

namespace App\Filament\Admin\Resources\PressReleaseResource\Pages;

use App\Filament\Admin\Resources\PressReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPressRelease extends EditRecord
{
    protected static string $resource = PressReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
