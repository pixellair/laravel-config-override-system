<?php
namespace ConfigOverrideSystem\Tests\Unit\Services;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use ConfigOverrideSystem\Repositories\ConfigOverrideRepository;
use ConfigOverrideSystem\Services\ConfigOverrideService;
use ConfigOverrideSystem\Tests\TestCase;
use function config;

class ConfigOverrideServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_overrides_from_repository_into_config(){
        $service = app('config-override');
        $service->set('app.name', 'OverriddenName');
        $service->set('app.debug', false);
        $service->load();
        $this->assertEquals('OverriddenName', config('app.name'));
        $this->assertEquals(false, config('app.debug'));
    }

    public function test_it_loads_overrides_from_cache_when_enabled(){
        Cache::forget(config('config-override.cache_prefix'));
        $service = app('config-override');
        $this->assertFalse(config('app.debug'));
        $service->set('app.debug', true);
        $service->load();
        $cache_value = Cache::get(config('config-override.cache_prefix'));
        $this->assertNotNull($cache_value);
        $this->assertArrayHasKey('app.debug', $cache_value);
        $this->assertEquals(true,$cache_value['app.debug']);
        $this->assertEquals(true,config('app.debug') );
    }

    public function test_it_sets_value_and_updates_repository(){
        $repository = new ConfigOverrideRepository();
        $service = new ConfigOverrideService($repository);
        $this->assertFalse(config('app.debug'));
        $service->set("app.debug", true);
        $service->load();
        $this->assertEquals( true, config('app.debug'));
        $this->assertEquals(true, $repository->get('app.debug') );
    }

    public function test_it_returns_default_when_key_missing(){
        $service = app('config-override');
        $this->assertNull(config('nonexistent.key'));
        $this->assertEquals('default_value', $service->get('nonexistent.key', 'default_value'));
    }

    public function test_delete(){
        $service = app('config-override');
        $service->set('app.test', 'testPackage');
        $this->assertNotNull(config('app.test'));
        $service->delete('app.test');
        $this->assertNull(config('app.test'));
    }
}
