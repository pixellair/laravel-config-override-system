<?php
namespace Pixellair\ConfigOverrideSystem\Facades;
use Illuminate\Support\Facades\Facade;

class ConfigOverride extends Facade
{
    public static function getFacadeAccessor(){
        return 'config-override';
    }
}
