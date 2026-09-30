<?php

namespace App\Filament\Admin\Resources\LanguageResource\Pages;

use App\Filament\Admin\Resources\LanguageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
