<?php

namespace App\Filament\Admin\Resources\TalkShowSettingResource\Pages;

use App\Filament\Admin\Resources\TalkShowSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalkShowSettings extends ListRecords
{
    protected static string $resource = TalkShowSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
