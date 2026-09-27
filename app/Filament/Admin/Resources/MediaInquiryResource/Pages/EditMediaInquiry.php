<?php

namespace App\Filament\Admin\Resources\MediaInquiryResource\Pages;

use App\Filament\Admin\Resources\MediaInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaInquiry extends EditRecord
{
    protected static string $resource = MediaInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
