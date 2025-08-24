<?php
namespace ConfigOverrideSystem\Interfaces;
interface ConfigOverrideStorageInterface
{
    public function set(string $key, mixed $value = null): void;
    public function get(string $key, mixed $default): mixed;
    public function all(): array;
    public function delete(string $key): void;
}
