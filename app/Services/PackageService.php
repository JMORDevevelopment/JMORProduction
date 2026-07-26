<?php

namespace App\Services;

use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\SystemPrice;

class PackageService
{
    public function listAll(): array
    {
        return Package::orderBy('priority', 'asc')->get()->all();
    }

    public function byCategory(string $categoryName): array
    {
        return Package::where('category_name', $categoryName)
            ->orderBy('priority', 'asc')
            ->get()
            ->all();
    }

    public function findById(int $packageId): array
    {
        return Package::where('id', $packageId)
            ->orderBy('priority', 'asc')
            ->get()
            ->map(fn ($item) => $item->toArray())
            ->toArray();
    }

    /**
     * Build the cart line item(s) for a package purchase, applying the
     * package's yearly discount when applicable.
     *
     * @return array{lines: array, checkoutType: string}
     */
    public function buildCartLines(int $packageId, ?int $serverQty, ?int $systemQty, string $packageType): array
    {
        $package = Package::where('id', $packageId)->first();

        if (! $package) {
            return ['lines' => [], 'checkoutType' => 'Monthly'];
        }

        $serverPrice = PackagePrice::where('package_id', $packageId)
            ->where('from_qty', '<=', $serverQty)
            ->where('to_qty', '>=', $serverQty)
            ->first();

        $systemPrice = SystemPrice::where('package_id', $packageId)
            ->where('from_qty', '<=', $systemQty)
            ->where('to_qty', '>=', $systemQty)
            ->first();

        $isYearly = $packageType === 'Yearly';
        $checkoutType = $isYearly ? 'Yearly' : 'Monthly';

        $serverTotal = $this->priceTotal($serverPrice?->pack_price, $serverQty, $isYearly ? $package->discount : 0);
        $systemTotal = $this->priceTotal($systemPrice?->system_price, $systemQty, $isYearly ? $package->discount : 0);

        $lines = [];

        if (! empty($serverQty) && $serverTotal > 0) {
            $lines[] = [
                'id' => $package->id.'p',
                'qty' => $serverQty,
                'type' => 'Server',
                'price' => $serverTotal,
                'name' => $package->name,
                'description' => $package->description,
            ];
        }

        if (! empty($systemQty) && $systemTotal > 0) {
            $lines[] = [
                'id' => $package->id.'s',
                'qty' => $systemQty,
                'type' => 'Workstation',
                'price' => $systemTotal,
                'name' => $package->name,
                'description' => $package->description,
            ];
        }

        return ['lines' => $lines, 'checkoutType' => $checkoutType];
    }

    private function priceTotal(?float $unitPrice, ?int $qty, float $discountPercent): float
    {
        if (! $unitPrice || ! $qty) {
            return 0;
        }

        if ($discountPercent > 0) {
            $unitPrice -= $unitPrice * $discountPercent / 100;
        }

        return $unitPrice * $qty;
    }

    public function recalculateLinePrice(int $packageId, string $type, int $qty, bool $isYearly): float
    {
        $package = Package::where('id', $packageId)->first();

        if (! $package) {
            return 0;
        }

        $discount = $isYearly ? $package->discount : 0;

        if ($type === 'Server') {
            $tier = PackagePrice::where('package_id', $packageId)
                ->where('from_qty', '<=', $qty)
                ->where('to_qty', '>=', $qty)
                ->first();

            return $this->priceTotal($tier?->pack_price, $qty, $discount);
        }

        $tier = SystemPrice::where('package_id', $packageId)
            ->where('from_qty', '<=', $qty)
            ->where('to_qty', '>=', $qty)
            ->first();

        return $this->priceTotal($tier?->system_price, $qty, $discount);
    }
}
