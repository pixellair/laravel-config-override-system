<?php

namespace ConfigOverrideSystem\Tests\Unit\Repositories;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ConfigOverrideSystem\Repositories\ConfigOverrideRepository;
use ConfigOverrideSystem\Tests\TestCase;
use function config;


class ConfigOverrideRepositoryTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_can_set_and_get_scalar_value(){
        $value = 'TestPackage';
        $repository = new ConfigOverrideRepository();
        $repository->set('app.name', $value);
        config($repository->all());
        $this->assertEquals(config('app.name'), $value);
        $this->assertEquals($repository->get('app.name'), $value);
    }

    public function test_it_can_set_and_get_json_value(){
        $value = ['TestPackageKey_1' => ['TestPackageKey_2' => 'TestPackageValue_1']];
        $repository = new ConfigOverrideRepository();
        $repository->set('app.testPackage', $value);
        config($repository->all());
        $config = config('app.testPackage');
        $result = is_string($config) ? json_decode($config, true) : $config;

        $this->assertArrayHasKey('TestPackageKey_1', $result);
        $this->assertArrayHasKey('TestPackageKey_1', $result);
    }

    public function test_it_returns_null_when_key_does_not_exist(){
        $repository = new ConfigOverrideRepository();
        $this->assertNull($repository->get('app.testPackage'));
        $this->assertNull(config('app.testPackage'));
    }

    public function test_it_updates_existing_key(){
        $value = 'TestPackage';
        $repository = new ConfigOverrideRepository();
        $this->assertNotNull(config('app.name'));
        $repository->set('app.name', $value);
        config($repository->all());
        $this->assertEquals($repository->get('app.name'), $value);
        $this->assertEquals(config('app.name'), $value);
    }

    public function test_it_can_override_multiple_keys()
    {
        $repository = new ConfigOverrideRepository();
        $repository->set('app.name', 'TestPackage');
        $repository->set('app.debug', true);

        config($repository->all());

        $this->assertEquals('TestPackage', config('app.name'));

        $this->assertTrue(config('app.debug') == true);
    }

    public function test_delete(){
        $repository = new ConfigOverrideRepository();
        $repository->set('app.test', 'TestPackage');
        $this->assertNotNull($repository->get('app.test'));
        $repository->delete('app.test');
        $this->assertNull($repository->get('app.test'));
    }
}
