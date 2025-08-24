<?php
namespace ConfigOverrideSystem\Tests\Feature\Facades;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ConfigOverrideSystem\Facades\ConfigOverride;
use ConfigOverrideSystem\Tests\TestCase;

class ConfigOverrideTest extends TestCase
{
    use RefreshDatabase;
    public function test_facade_get_returns_value(){
        $value_from_facades = ConfigOverride::get('app.name');
        $value_from_config = config('app.name');
        $this->assertEquals($value_from_config, $value_from_facades);
    }

    public function test_facade_set_stores_value()
    {
        ConfigOverride::set('app.name', 'FacadeTestApp');
        $this->assertEquals(config('app.name'), 'FacadeTestApp');
    }

    public function test_facade_load_applies_overrides_to_config(){
        ConfigOverride::set('app.name', 'FacadeTestApp');
        ConfigOverride::load();
        $this->assertEquals('FacadeTestApp', config('app.name'));
    }
}
