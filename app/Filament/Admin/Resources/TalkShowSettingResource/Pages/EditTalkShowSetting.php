<?php

namespace App\Filament\Admin\Resources\TalkShowSettingResource\Pages;

use App\Filament\Admin\Resources\TalkShowSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalkShowSetting extends EditRecord
{
    protected static string $resource = TalkShowSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
