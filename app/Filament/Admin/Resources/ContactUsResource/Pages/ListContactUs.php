<?php

namespace App\Filament\Admin\Resources\ContactUsResource\Pages;

use App\Filament\Admin\Resources\ContactUsResource;
use App\Models\ContactUs;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContactUs extends ListRecords
{
    protected static string $resource = ContactUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Distinct admin sections for each workflow (testing report issue 055).
     */
    public function getTabs(): array
    {
        $generalReasons = [
            'Already Client',
            'Donate a Product to be Unboxed',
            'General Inquiry',
            'Vendor',
        ];

        $supportReasons = [
            'Residential Support',
            'Business Support',
            'Support',
        ];

        return [
            'all' => Tab::make('All')
                ->badge(fn (): int => ContactUs::query()->count()),

            'general' => Tab::make('General')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereIn('reason', $generalReasons))
                ->badge(fn (): int => ContactUs::query()->whereIn('reason', $generalReasons)->count()),

            'assessment' => Tab::make('Technology Assessment')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('reason', 'Technology Assessment'))
                ->badge(fn (): int => ContactUs::query()->where('reason', 'Technology Assessment')->count()),

            'support' => Tab::make('Support')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereIn('reason', $supportReasons))
                ->badge(fn (): int => ContactUs::query()->whereIn('reason', $supportReasons)->count()),

            'employment' => Tab::make('Employment')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('reason', 'Employment & Internships'))
                ->badge(fn (): int => ContactUs::query()->where('reason', 'Employment & Internships')->count()),
        ];
    }
}
