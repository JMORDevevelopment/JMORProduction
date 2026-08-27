<?php

namespace App\Filament\Admin\Resources\RecommendedResource\Pages;

use App\Filament\Admin\Resources\RecommendedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditRecommended extends EditRecord
{
    protected static string $resource = RecommendedResource::class;

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
