<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = 'blog/'.Str::slug($data['name'] ?? '');

        return $data;
    }
}
