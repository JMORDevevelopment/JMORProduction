<?php

namespace App\Http\Controllers;

use App\Services\PackageService;

class PackageController extends Controller
{
    public function __construct(private PackageService $packages)
    {
    }

    public function list()
    {
        return view('frontend.packages', [
            'package_data' => $this->packages->listAll(),
            'title' => 'Packages',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function detail(string $categoryName)
    {
        return view('frontend.packages_detail', [
            'top_title' => $categoryName,
            'package_data' => $this->packages->byCategory($categoryName),
            'title' => 'Jmor Services',
            'description' => '',
            'keywords' => '',
        ]);
    }

    public function single(int $packageId)
    {
        return view('frontend.single_package', [
            'package_data' => $this->packages->findById($packageId),
            'title' => 'Jmor Services',
            'description' => '',
            'keywords' => '',
        ]);
    }
}
