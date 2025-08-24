# 🎟️ Laravel Config Override System

A reusable, Composer-installable **config override system** for Laravel projects.

## 🚀 Installation

### 1. Require via Composer

```
composer require pixellair/laravel-config-override-system
```
### 2. Publish and Run Migrations
To create the necessary tables for storing discounts and their usage:

```bash
php artisan vendor:publish --tag=migrations
php artisan migrate
```
### 3. Publish Configs

```bash
php artisan vendor:publish --provider="ConfigOverrideSystem\ConfigOverrideServiceProvider" --tag=config
```

###⚙️ Usage
```php
use Pixellair\ConfigOverrideSystem\Facades\ConfigOverride as ConfigOverrideFacade;

ConfigOverrideFacade::set('app.name', 'new project');
echo config('app.name'); // return the value `new project`
echo ConfigOverrideFacade::get('app.name'); // return the value `new project` from database

ConfigOverrideFacade::delete('app.name');
echo config('app.name'); // return the value inside configs/app.php
echo ConfigOverrideFacade::get('app.name'); // return the value inside configs/app.php
```
