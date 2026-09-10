<?php

namespace App\Filament\Admin\Resources\PackageResource\Pages;

use App\Filament\Admin\Resources\PackageResource;
use App\Models\PackagePrice;
use App\Models\SystemPrice;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['link'] = Str::slug($data['name']);
        $data['image'] = $data['image'] ?? '';
        $data['description'] = $data['description'] ?? '';
        $data['price'] = $data['price'] ?? '';
        $data['upfront'] = $data['upfront'] ?? '';

        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->data;

        foreach ($data['serverPrices'] ?? [] as $item) {
            if (! empty($item['pack_price']) && ! empty($item['from_qty']) && ! empty($item['to_qty'])) {
                PackagePrice::create([
                    'package_id' => $this->record->id,
                    'pack_price' => $item['pack_price'],
                    'from_qty' => $item['from_qty'],
                    'to_qty' => $item['to_qty'],
                ]);
            }
        }

        foreach ($data['systemPrices'] ?? [] as $item) {
            if (! empty($item['system_price']) && ! empty($item['from_qty']) && ! empty($item['to_qty'])) {
                SystemPrice::create([
                    'package_id' => $this->record->id,
                    'system_price' => $item['system_price'],
                    'from_qty' => $item['from_qty'],
                    'to_qty' => $item['to_qty'],
                ]);
            }
        }
    }
}
