<?php
namespace ConfigOverrideSystem\Models;
use Illuminate\Database\Eloquent\Model;

class ConfigOverride extends model{

    protected $fillable = ['key', 'value', 'is_json'];
}
