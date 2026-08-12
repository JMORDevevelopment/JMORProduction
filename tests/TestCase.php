<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Pin the test suite to the dedicated MySQL test database declared in
     * phpunit.xml.
     *
     * Laravel's env() helper resolves $_SERVER first, but PHPUnit only
     * overrides putenv() and $_ENV. The DB_* values loaded from .env during
     * the "php artisan test" boot would otherwise win and make the suite hit
     * the local jmor_web database. Setting the variables here, before the
     * test application boots, keeps every test isolated.
     */
    protected function setUp(): void
    {
        $_SERVER['DB_CONNECTION'] = 'mysql';
        $_SERVER['DB_DATABASE'] = 'jmor_web_test';
        $_ENV['DB_CONNECTION'] = 'mysql';
        $_ENV['DB_DATABASE'] = 'jmor_web_test';
        putenv('DB_CONNECTION=mysql');
        putenv('DB_DATABASE=jmor_web_test');

        parent::setUp();
    }
}
