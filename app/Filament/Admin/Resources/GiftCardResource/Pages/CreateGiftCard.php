<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftCard extends CreateRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']));
        $data['link'] = trim($data['link'], '-');

        // Ensure image defaults to empty string if null
        $data['image'] = $data['image'] ?? '';
        $data['description'] = $data['description'] ?? '';
        $data['category'] = $data['category'] ?? '';

        return $data;
    }
}
