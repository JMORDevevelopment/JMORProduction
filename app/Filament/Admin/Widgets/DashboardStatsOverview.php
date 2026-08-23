<?php

namespace App\Filament\Admin\Widgets;

use App\Models\CouponCheckout;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Overview';

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->icon('heroicon-o-briefcase')
                ->color('primary'),

            Stat::make('New Orders', Order::where('status', 1)->count())
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->color('warning'),

            Stat::make('Gift Cards', CouponCheckout::where('status', 1)->count())
                ->icon('heroicon-o-gift')
                ->color('danger'),
        ];
    }
}
