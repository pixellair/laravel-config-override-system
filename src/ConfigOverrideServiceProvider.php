<?php
namespace ConfigOverrideSystem;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ConfigOverrideSystem\Repositories\ConfigOverrideRepository;
use ConfigOverrideSystem\Services\ConfigOverrideService;

class ConfigOverrideServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('config-override', function () {
            return new ConfigOverrideService(new ConfigOverrideRepository());
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Database/migrations/' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/Configs/config-override.php' => config_path('config-override.php')
            ], 'config');
        }

        if(Schema::hasTable('config_overrides')){
            $this->app->make(ConfigOverrideService::class)->load();
        }

    }
}
