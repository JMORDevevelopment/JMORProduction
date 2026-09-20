<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiftCard extends EditRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['link'] = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $data['name']));
        $data['link'] = trim($data['link'], '-');

        // FileUpload and Textarea return null when empty
        $data['description'] = $data['description'] ?? '';
        $data['image'] = $data['image'] ?? '';
        $data['category'] = $data['category'] ?? '';
        $data['coupon_number'] = $data['coupon_number'] ?? '';

        return $data;
    }
}
