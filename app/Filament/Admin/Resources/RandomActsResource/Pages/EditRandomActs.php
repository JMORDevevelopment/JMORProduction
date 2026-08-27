<?php

namespace App\Filament\Admin\Resources\RandomActsResource\Pages;

use App\Filament\Admin\Resources\RandomActsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditRandomActs extends EditRecord
{
    protected static string $resource = RandomActsResource::class;

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
