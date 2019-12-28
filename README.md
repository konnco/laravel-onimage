# Laravel Transeloquent

**If you want the faster way to translate your model and store it in a single table, this package is built for you.**

[![Build Status](https://travis-ci.org/Konnco/laravel-transeloquent.svg?branch=master)](https://travis-ci.org/Konnco/laravel-transeloquent)
[![Latest Stable Version](https://poser.pugx.org/konnco/laravel-transeloquent/v/stable)](https://packagist.org/packages/konnco/laravel-transeloquent)
[![Total Downloads](https://poser.pugx.org/konnco/laravel-transeloquent/downloads)](https://packagist.org/packages/konnco/laravel-transeloquent)
[![Latest Unstable Version](https://poser.pugx.org/konnco/laravel-transeloquent/v/unstable)](https://packagist.org/packages/konnco/laravel-transeloquent)
[![License](https://poser.pugx.org/konnco/laravel-transeloquent/license)](https://packagist.org/packages/konnco/laravel-transeloquent)
[![StyleCI](https://github.styleci.io/repos/225027362/shield?branch=master)](https://github.styleci.io/repos/225027362)

This is a Laravel package for translatable models. Its goal is to remove the complexity in retrieving and storing multilingual model instances. With this package you write less code, as the translations are being fetched/saved when you fetch/save your instance.

Maybe out there there's so many package that work the same way, and has more performance, but the purpose this package is make your development time faster.

***This package is still in alpha version, so the update may broke your application.***

## Installation
```php
composer require konnco/laravel-transeloquent
```

```php
php artisan vendor:publish
```

```php
php artisan migrate
```

## Configuration
you can find transeloquent configuration here. `config/transeloquent.php`

```php
return [
    // default locale
    'locale' => 'en',
    
    // transeloquent model
    'model' => Konnco\Transeloquent\models\Transeloquent::class
]; 
```

Add transeloquent traits into your model

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {
    use \Konnco\Transeloquent\Transeloquent;
}
```

and the default excluded field is `id`, `created_at`, `updated_at` these fields will not saved into database.

if you want to add only some fields to be translated, you may have to add `$translateOnly` into your model.
```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Konnco\Transeloquent\Transeloquent;

class News extends Model {
    use Transeloquent;
    
    protected $translateOnly = ['translate-field-1', 'translate-field-2'];
}
```

if you want to add more excluded field from translated, you may have to add `$translateExcept` into your model.

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Konnco\Transeloquent\Transeloquent;

class News extends Model {
    use Transeloquent;
    
    protected $translateExcept = ['dont-translate-1', 'dont-translate-2'];
}
```
**Note** : If you have set `$translateOnly` variable, it will be executed first. Make sure you don't use `$translateOnly` variable in your model if you want to use `$translateExcept` variable.

## Quick Example
### Getting translated attributes
Original Attributes In English or based on configuration in `app.transeloquent.default_locale`
```php
//in the original language
$post = Post::first();
echo $post->title; // My first post
```
---
Translated attributes
```php
App::setLocale('id');
$post = Post::first();
echo $post->title; // Post Pertama Saya
```

### Saving translated attributes
To save translation you must have the initial data.

for example you want to save indonesian translation.
```php
App::setLocale('id');
$post = Post::first();
$post->title = "Post Pertama Saya";
$post->save();

// or set locale for specific model

$post = Post::first();
$post->setLocale('id')
$post->title = "Post Pertama Saya";
$post->save();
```

### Checking if Translation Available
```php
$post = Post::first();
$post->translationExist('id'); //return boolean
```

## Authors

* **Franky So** - *Initial work* - [Konnco](https://github.com/konnco)
* **Rizal Nasution** - *Initial work* - [Konnco](https://github.com/konnco)
