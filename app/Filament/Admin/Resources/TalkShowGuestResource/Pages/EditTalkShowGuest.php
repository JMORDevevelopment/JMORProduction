<?php

namespace App\Filament\Admin\Resources\TalkShowGuestResource\Pages;

use App\Filament\Admin\Resources\TalkShowGuestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalkShowGuest extends EditRecord
{
    protected static string $resource = TalkShowGuestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
