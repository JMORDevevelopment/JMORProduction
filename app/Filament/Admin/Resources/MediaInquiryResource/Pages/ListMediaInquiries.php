<?php

namespace App\Filament\Admin\Resources\MediaInquiryResource\Pages;

use App\Filament\Admin\Resources\MediaInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaInquiries extends ListRecords
{
    protected static string $resource = MediaInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
