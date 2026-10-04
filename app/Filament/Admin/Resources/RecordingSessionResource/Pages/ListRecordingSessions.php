<?php

namespace App\Filament\Admin\Resources\RecordingSessionResource\Pages;

use App\Filament\Admin\Resources\RecordingSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecordingSessions extends ListRecords
{
    protected static string $resource = RecordingSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
