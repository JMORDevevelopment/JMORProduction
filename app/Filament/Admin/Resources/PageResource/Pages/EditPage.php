<?php

namespace App\Filament\Admin\Resources\PageResource\Pages;

use App\Filament\Admin\Resources\PageResource;
use App\Models\Menu;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    $this->deleteMenuEntry();
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['link'] = Str::slug($data['name'] ?? '');

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncMenuEntry();
    }

    /**
     * CI logic: sync menu entry based on menu_status.
     * If menu_status=1, create/update menu entry.
     * If menu_status=0, delete menu entry.
     */
    private function syncMenuEntry(): void
    {
        $page = $this->record;

        $existingMenu = Menu::where('page_id', $page->id)
            ->where('menu_type', 'Pages')
            ->first();

        if ($page->menu_status) {
            $position = ($page->menu_location == 0) ? 100 : 0;

            if ($existingMenu) {
                $existingMenu->update([
                    'parent_id' => $page->menu_location,
                    'title' => $page->name,
                    'url' => $page->link,
                    'position' => $position,
                ]);
            } else {
                Menu::create([
                    'parent_id' => $page->menu_location,
                    'title' => $page->name,
                    'url' => $page->link,
                    'page_id' => $page->id,
                    'position' => $position,
                    'menu_type' => 'Pages',
                ]);
            }
        } elseif ($existingMenu) {
            $existingMenu->delete();
        }
    }

    /**
     * CI logic: when page is deleted, also delete its menu entry.
     */
    private function deleteMenuEntry(): void
    {
        $pageId = $this->record->id;

        Menu::where('page_id', $pageId)
            ->where('menu_type', 'Pages')
            ->delete();
    }
}
