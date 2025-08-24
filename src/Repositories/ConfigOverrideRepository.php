<?php

namespace ConfigOverrideSystem\Repositories;
use ConfigOverrideSystem\Interfaces\ConfigOverrideStorageInterface;
use ConfigOverrideSystem\Models\ConfigOverride;

class ConfigOverrideRepository implements ConfigOverrideStorageInterface
{

    public function all(): array{
        return ConfigOverride::all()
            ->mapWithKeys(fn($c) => [$c->key => $c->is_json ? json_decode($c->value, true) : $c->value])
            ->toArray();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $config = ConfigOverride::where('key', $key)->first();

        if (!$config){
            return $default;
        }

        return $config->is_json ? json_decode($config->value, true) : $config->value;
    }

    public function set(string $key, mixed $value = null): void
    {
        $is_json = is_array($value) || is_object($value);

        ConfigOverride::updateOrCreate(
            ['key' => $key],
            [
                'value' => $is_json ? json_encode($value) : $value,
                'is_json' => $is_json,
            ]
        );
    }

    public function delete(string $key): void{
        $config = ConfigOverride::where('key', $key)->first();

        if($config){
            $config->delete();
        }
    }

}
