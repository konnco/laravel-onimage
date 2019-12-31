# Laravel Onimage
[![Build Status](https://travis-ci.org/Konnco/laravel-onimage.svg?branch=master)](https://travis-ci.org/Konnco/laravel-onimage)
[![Latest Stable Version](https://poser.pugx.org/konnco/laravel-onimage/v/stable)](https://packagist.org/packages/konnco/laravel-onimage)
[![Total Downloads](https://poser.pugx.org/konnco/laravel-onimage/downloads)](https://packagist.org/packages/konnco/laravel-onimage)
[![Latest Unstable Version](https://poser.pugx.org/konnco/laravel-onimage/v/unstable)](https://packagist.org/packages/konnco/laravel-onimage)
[![License](https://poser.pugx.org/konnco/laravel-onimage/license)](https://packagist.org/packages/konnco/laravel-onimage)
[![StyleCI](https://github.styleci.io/repos/228747586/shield?branch=master)](https://github.styleci.io/repos/228747586)

This package is designed to boost up your developing time in managing your image in Laravel framework.

This package based on the famous [Intervention/Image](http://image.intervention.io)

***This package is still in alpha version, so the update may broke your application.***

## Installation
```php
composer require composer require konnco/laravel-onimage
```

```php
php artisan vendor:publish
```

```php
php artisan migrate
```

## Configuration
you can find onimage configuration here. `config/onimage.php`

```php
    /*
    |--------------------------------------------------------------------------
    | Image Upload Drivers
    |--------------------------------------------------------------------------
    |
    | define driver you should use to upload your image.
    |
    */

    'driver' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Available image sizes
    |--------------------------------------------------------------------------
    |
    | define driver you should use to upload your image.
    |
    | size example original :
    | * width
    | * height
    | * position
    |       * top-left
    |       * top
    |       * top-right
    |       * left
    |       * center (default)
    |       * right
    |       * bottom-left
    |       * bottom
    |       * bottom-right
    |
    */
    'sizes' => [
        'original' => [null, null],
        'more-size' => [width, height, position]
    ],
```

Add onimage traits into your model

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {
    use \Konnco\Onimage\Onimage;
}
```

and then define your field for images.

in this package we separate our image type into 2 section
* single (usually used for featured image)
* multiple (usually used for gallery image)

in your model define protected field named `protected $imageAttributes` following example below :
```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Konnco\Onimage\Onimage;

class News extends Model {
    use Onimage;
    
    protected $imageAttributes = [
                                    'cover'     => '',
                                    'galleries' => 'multiple|sizes:original,square|nullable',
                                 ];
}
```

Available Rules :
1. `multiple` these is used to define multiple image into field.
2. `sizes:configsize1,configsize2` these is used to define current field is going to resize into config size that you define in `config/onimage.php`.
3. `nullable` these is used to define image field can be nulled.

## Quick Example
### Upload your image
```php
$fruit = new Fruit();
$fruit->name = 'banana';
$fruit->cover = 'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80';
$fruit->galleries = [
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
];
$fruit->save();
```

## Upload Type
You can insert these types into onimage field :
* string - Path of the image in filesystem.
* string - URL of an image (allow_url_fopen must be enabled).
* string - Binary image data.
* string - Data-URL encoded image data.
* string - Base64 encoded image data.
* resource - PHP resource of type gd. (when using GD driver)
* object - Imagick instance (when using Imagick driver)
* object - Intervention\Image\Image instance
* object - SplFileInfo instance (To handle Laravel file uploads via Symfony\Component\HttpFoundation\File\UploadedFile)

## Authors

[//]: contributor-faces
<a href="https://github.com/frankyso"><img src="https://avatars.githubusercontent.com/u/5705520?v=3" title="frankyso" width="80" height="80"></a>
