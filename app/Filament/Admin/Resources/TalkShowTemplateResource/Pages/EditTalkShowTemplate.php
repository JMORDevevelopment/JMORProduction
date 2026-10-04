<?php

namespace App\Filament\Admin\Resources\TalkShowTemplateResource\Pages;

use App\Filament\Admin\Resources\TalkShowTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalkShowTemplate extends EditRecord
{
    protected static string $resource = TalkShowTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
