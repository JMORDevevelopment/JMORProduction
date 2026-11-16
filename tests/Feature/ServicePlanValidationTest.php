<?php

use App\Models\HomeTab;

const PLAN_MIGRATION = '2026_11_13_171500_normalize_published_service_plans.php';

function runPlanMigration(): void
{
    $migration = include database_path('migrations/'.PLAN_MIGRATION);
    $migration->up();
}

function servicePlan(array $attributes = []): HomeTab
{
    return HomeTab::create(array_merge([
        'tab_title' => 'JMOR Business Standard',
        'tab_description' => 'Focus on your business while we handle your tech.',
        'tab_list' => ['Proactive remote monitoring'],
        'benefits' => ['24/7 remote support'],
        'cost' => ['$99 per month per system'],
    ], $attributes));
}

test('the homepage only publishes plans with an approved price and inclusions', function () {
    servicePlan();

    HomeTab::create([
        'tab_title' => 'asd',
        'tab_description' => 'asd',
        'tab_list' => ['Feature'],
        'benefits' => ['sdfdf'],
        'cost' => ['12'],
    ]);

    HomeTab::create([
        'tab_title' => 'Draft plan',
        'tab_description' => '',
        'tab_list' => [],
        'benefits' => [],
        'cost' => [],
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('JMOR Business Standard')
        ->assertSee('$99 per month per system')
        ->assertDontSee('asd')
        ->assertDontSee('Draft plan');
});

test('the compare table shows the published CMS pricing instead of hard-coded prices', function () {
    servicePlan(['cost' => ['$123 per month per system', 'Each server is $321/ month']]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Published pricing')
        ->assertSee('$123 per month per system')
        ->assertSee('Each server is $321/ month')
        ->assertDontSee('Cost per system')
        ->assertDontSee('$150/mo')
        ->assertDontSee('$250/mo');
});

test('the plan migration removes leftover test plans and corrects plan copy', function () {
    HomeTab::create([
        'tab_title' => 'asd',
        'tab_description' => 'asd',
        'tab_list' => ['Feature'],
        'benefits' => ['sdfdf'],
        'cost' => ['12'],
    ]);

    $businessManager = servicePlan([
        'tab_title' => 'JMOR Business Manager',
        'tab_description' => 'With the JMOR Business Manager plan, enjoy all the benefits of the JMOR Business Standard.',
        'cost' => ['Each server is $150/ month'],
    ]);

    $plan = servicePlan([
        'tab_title' => 'JMOR Remote Home Update & Monitoring Service',
        'tab_description' => 'Your Business is an important JMOR as it is to you. With the JMOR Business Manager plan, enjoy the benefits.',
        'tab_list' => [
            'Keep your system updated with the latest security and software patchesasd',
            'Bi-annual on-site visits for hardware inspection and maintenence.',
        ],
        'benefits' => ['Proactive remote diagnostics and disgnostics'],
    ]);

    runPlanMigration();

    $plan->refresh();

    expect(HomeTab::where('tab_title', 'asd')->exists())->toBeFalse()
        ->and($businessManager->refresh()->tab_description)->toContain('With the JMOR Business Manager plan')
        ->and($plan->tab_description)->toContain('Your business is as important to JMOR as it is to you.')
        ->and($plan->tab_description)->toContain('With the JMOR Remote Home Update & Monitoring Service plan')
        ->and($plan->tab_list[0])->toContain('software patches')
        ->and($plan->tab_list[0])->not->toContain('patchesasd')
        ->and($plan->tab_list[1])->toContain('maintenance')
        ->and($plan->benefits[0])->toContain('diagnostics')
        ->and($plan->cost)->toBe(['$99 per month per system']);
});

test('the plan migration is idempotent', function () {
    servicePlan(['tab_list' => ['Keep patchesasd current']]);

    runPlanMigration();
    runPlanMigration();

    expect(HomeTab::where('tab_title', 'JMOR Business Standard')->count())->toBe(1)
        ->and(HomeTab::where('tab_title', 'JMOR Business Standard')->first()->tab_list)
        ->toBe(['Keep patches current']);
});
