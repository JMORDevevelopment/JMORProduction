<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PackageService
{
    public function listAll(): array
    {
        return DB::table('packages')->orderBy('priority', 'asc')->get()->toArray();
    }

    public function byCategory(string $categoryName): array
    {
        return DB::table('packages')
            ->where('category_name', $categoryName)
            ->orderBy('priority', 'asc')
            ->get()
            ->toArray();
    }

    public function findById(int $packageId): array
    {
        return DB::table('packages')
            ->where('id', $packageId)
            ->orderBy('priority', 'asc')
            ->get()
            ->map(fn ($item) => (array) $item)
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
        $package = DB::table('packages')->where('id', $packageId)->first();

        if (! $package) {
            return ['lines' => [], 'checkoutType' => 'Monthly'];
        }

        $serverPrice = DB::table('package_price')
            ->where('package_id', $packageId)
            ->where('from_qty', '<=', $serverQty)
            ->where('to_qty', '>=', $serverQty)
            ->first();

        $systemPrice = DB::table('system_price')
            ->where('package_id', $packageId)
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
}
