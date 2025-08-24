<?php
namespace Pixellair\ConfigOverrideSystem\Tests\Feature\Providers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Pixellair\ConfigOverrideSystem\ConfigOverrideServiceProvider;
use Pixellair\ConfigOverrideSystem\Repositories\ConfigOverrideRepository;
use Pixellair\ConfigOverrideSystem\Services\ConfigOverrideService;
use Pixellair\ConfigOverrideSystem\Tests\TestCase;

class ConfigOverrideServiceProviderTest extends TestCase
{
    use RefreshDatabase;
    public function test_service_is_registered_in_container(){
        $service = $this->app->make('config-override');
        $this->assertInstanceOf(ConfigOverrideService::class, $service);
    }

    public function test_service_is_singleton(){
        $firstInstance = $this->app->make('config-override');
        $secondInstance = $this->app->make('config-override');
        $this->assertSame($firstInstance, $secondInstance);
    }

    public function test_service_load_is_called_on_boot(){
        $repository = new ConfigOverrideRepository();
        $service = new ConfigOverrideService($repository);
        $service->set('app.debug', true);
        $provider = $this->app->getProvider(ConfigOverrideServiceProvider::class);
        $provider->boot();
        $this->assertEquals(true,config('app.debug'));
    }
}
