<?php

namespace App\Filament\Admin\Resources\RandomActsResource\Pages;

use App\Filament\Admin\Resources\RandomActsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRandomAct extends EditRecord
{
    protected static string $resource = RandomActsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
