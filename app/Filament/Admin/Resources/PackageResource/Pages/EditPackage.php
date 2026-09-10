<?php

namespace App\Filament\Admin\Resources\PackageResource\Pages;

use App\Filament\Admin\Resources\PackageResource;
use App\Models\PackagePrice;
use App\Models\SystemPrice;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackage extends EditRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['image'] = $data['image'] ?? $this->record->image ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->data;
        $packageId = $this->record->id;

        // Sync server prices — delete existing, re-create from form data
        PackagePrice::where('package_id', $packageId)->delete();

        foreach ($data['serverPrices'] ?? [] as $item) {
            if (! empty($item['pack_price']) && ! empty($item['from_qty']) && ! empty($item['to_qty'])) {
                PackagePrice::create([
                    'package_id' => $packageId,
                    'pack_price' => $item['pack_price'],
                    'from_qty' => $item['from_qty'],
                    'to_qty' => $item['to_qty'],
                ]);
            }
        }

        // Sync system prices
        SystemPrice::where('package_id', $packageId)->delete();

        foreach ($data['systemPrices'] ?? [] as $item) {
            if (! empty($item['system_price']) && ! empty($item['from_qty']) && ! empty($item['to_qty'])) {
                SystemPrice::create([
                    'package_id' => $packageId,
                    'system_price' => $item['system_price'],
                    'from_qty' => $item['from_qty'],
                    'to_qty' => $item['to_qty'],
                ]);
            }
        }
    }
}
