<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\PageResource;
use App\Models\Menu;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncMenuEntry();
    }

    /**
     * CI logic: if menu_status=1, insert into menu table.
     */
    private function syncMenuEntry(): void
    {
        $page = $this->record;

        if (! $page->menu_status) {
            return;
        }

        $position = ($page->menu_location == 0) ? 100 : 0;

        Menu::create([
            'parent_id' => $page->menu_location,
            'title' => $page->name,
            'url' => $page->link,
            'page_id' => $page->id,
            'position' => $position,
            'menu_type' => 'Pages',
        ]);
    }
}
