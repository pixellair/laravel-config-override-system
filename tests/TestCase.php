<?php

namespace ConfigOverrideSystem\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ConfigOverrideSystem\ConfigOverrideServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('database.default', 'testing');
        $this->app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../src/Database/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('config-override.is_config_override_enable', true);
        $app['config']->set('config-override.is_caching_enable', true);
        $app['config']->set('config-override.cache_prefix', 'test_config_override');
    }

    protected function getPackageProviders($app)
    {
        return [
            ConfigOverrideServiceProvider::class,
        ];
    }

}

