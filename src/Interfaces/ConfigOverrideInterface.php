<?php
namespace Pixellair\ConfigOverrideSystem\Interfaces;
interface ConfigOverrideInterface
{
    public function load(): void;
    public function set(string $key, mixed $default = null): void;
    public function get(string|array $key, mixed $default = null): mixed;
    public function delete(string $key): void;
}
