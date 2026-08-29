<?php

namespace App\Filament\Admin\Resources\HomeTabResource\Pages;

use App\Filament\Admin\Resources\HomeTabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeTab extends EditRecord
{
    protected static string $resource = HomeTabResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tab_list'] = $this->convertToRepeater($data['tab_list'] ?? []);
        $data['benefits'] = $this->convertToRepeater($data['benefits'] ?? []);
        $data['cost'] = $this->convertToRepeater($data['cost'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['tab_list'] = $this->convertFromRepeater($data['tab_list'] ?? []);
        $data['benefits'] = $this->convertFromRepeater($data['benefits'] ?? []);
        $data['cost'] = $this->convertFromRepeater($data['cost'] ?? []);

        return $data;
    }

    /**
     * Convert flat array ["item1", "item2"] to repeater format [["item" => "item1"], ...].
     */
    private function convertToRepeater(array $items): array
    {
        $filtered = array_filter($items, fn ($item) => filled($item));

        return array_map(fn ($item) => is_array($item) ? $item : ['item' => $item], array_values($filtered));
    }

    /**
     * Convert repeater format [["item" => "item1"], ...] back to flat array ["item1", "item2"].
     */
    private function convertFromRepeater(array $items): array
    {
        return array_map(fn ($item) => is_array($item) ? ($item['item'] ?? reset($item)) : $item, $items);
    }
}
