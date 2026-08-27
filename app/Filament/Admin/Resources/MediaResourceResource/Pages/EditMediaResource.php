<?php

namespace App\Filament\Admin\Resources\MediaResourceResource\Pages;

use App\Filament\Admin\Resources\MediaResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditMediaResource extends EditRecord
{
    protected static string $resource = MediaResourceResource::class;

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
