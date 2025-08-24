<?php
namespace ConfigOverrideSystem\Services;
use Illuminate\Support\Facades\Cache;
use ConfigOverrideSystem\Interfaces\ConfigOverrideInterface;
use ConfigOverrideSystem\Repositories\ConfigOverrideRepository;

class ConfigOverrideService implements ConfigOverrideInterface
{

    private bool $isCacheEnabled;
    private bool $isConfigOverrideEnabled;
    private string $cache_prefix;
    private ConfigOverrideRepository $repository;

    public function __construct(ConfigOverrideRepository $repository){
        $this->isConfigOverrideEnabled = config('config-override.is_config_override_enable',true);
        $this->isCacheEnabled = config('config-override.is_caching_enable',true);
        $this->cache_prefix = config('config-override.cache_prefix','config_override');
        $this->repository = $repository;
    }

    public function load(): void
    {
        if(!$this->isConfigOverrideEnabled){
            return ;
        }

        if ($this->isCacheEnabled) {
            $overrides = Cache::rememberForever($this->cache_prefix, fn () => $this->repository->all());
        } else {
            $overrides = $this->repository->all();
        }

        config($this->flatten($overrides));
    }

    public function get(string|array $key, mixed $default = null):mixed
    {
        if(!$this->isConfigOverrideEnabled){
            return config($key, $default);
        }
        $value = $this->repository->get($key, $default);
        return $value ?? config($key, $default);
    }

    public function set(string $key, mixed $value = null): void{
        $this->repository->set($key, $value);
        config([$key => $value]);
        if ($this->isCacheEnabled) {
            $all = $this->repository->all();
            Cache::forever($this->cache_prefix, $all);
        }
    }

    public function delete(string $key): void{
        $this->repository->delete($key);
        config()->offsetUnset($key);

        if ($this->isCacheEnabled) {
            Cache::forget($this->cache_prefix);
        }
    }

    private function flatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (is_array($value)) {
                $result += $this->flatten($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

}
