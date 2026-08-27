<?php

namespace App\Filament\Admin\Resources\BrandGuidelineResource\Pages;

use App\Filament\Admin\Resources\BrandGuidelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditBrandGuideline extends EditRecord
{
    protected static string $resource = BrandGuidelineResource::class;

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
