<?php

namespace App\Filament\Admin\Resources\HomeTabResource\Pages;

use App\Filament\Admin\Resources\HomeTabResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeTab extends CreateRecord
{
    protected static string $resource = HomeTabResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tab_list'] = $this->convertFromRepeater($data['tab_list'] ?? []);
        $data['benefits'] = $this->convertFromRepeater($data['benefits'] ?? []);
        $data['cost'] = $this->convertFromRepeater($data['cost'] ?? []);

        return $data;
    }

    private function convertFromRepeater(array $items): array
    {
        return array_map(fn ($item) => is_array($item) ? ($item['item'] ?? reset($item)) : $item, $items);
    }
}
