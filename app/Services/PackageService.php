<?php

namespace App\Services;

<<<<<<< HEAD
use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\SystemPrice;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

class PackageService
{
    public function listAll(): array
    {
<<<<<<< HEAD
        return Package::orderBy('priority', 'asc')->get()->all();
=======
        return DB::table('packages')->orderBy('priority', 'asc')->get()->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
    }

    public function byCategory(string $categoryName): array
    {
<<<<<<< HEAD
        return Package::where('category_name', $categoryName)
            ->orderBy('priority', 'asc')
            ->get()
            ->all();
=======
        return DB::table('packages')
            ->where('category_name', $categoryName)
            ->orderBy('priority', 'asc')
            ->get()
            ->toArray();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
    }

    public function findById(int $packageId): array
    {
<<<<<<< HEAD
        return Package::where('id', $packageId)
            ->orderBy('priority', 'asc')
            ->get()
            ->map(fn ($item) => $item->toArray())
=======
        return DB::table('packages')
            ->where('id', $packageId)
            ->orderBy('priority', 'asc')
            ->get()
            ->map(fn ($item) => (array) $item)
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
<<<<<<< HEAD
        $package = Package::where('id', $packageId)->first();
=======
        $package = DB::table('packages')->where('id', $packageId)->first();
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)

        if (! $package) {
            return ['lines' => [], 'checkoutType' => 'Monthly'];
        }

<<<<<<< HEAD
        $serverPrice = PackagePrice::where('package_id', $packageId)
=======
        $serverPrice = DB::table('package_price')
            ->where('package_id', $packageId)
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
            ->where('from_qty', '<=', $serverQty)
            ->where('to_qty', '>=', $serverQty)
            ->first();

<<<<<<< HEAD
        $systemPrice = SystemPrice::where('package_id', $packageId)
=======
        $systemPrice = DB::table('system_price')
            ->where('package_id', $packageId)
>>>>>>> f3ffa73 (refactor: split fat HomeController into domain controllers/services, add Blade mail templates)
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
