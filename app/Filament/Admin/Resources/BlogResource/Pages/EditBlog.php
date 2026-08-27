<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['link'] = 'blog/'.Str::slug($data['name'] ?? '');

        return $data;
    }
}
