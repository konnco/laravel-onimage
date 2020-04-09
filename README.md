# Laravel Onimage
[![Build Status](https://travis-ci.org/Konnco/laravel-onimage.svg?branch=master)](https://travis-ci.org/Konnco/laravel-onimage)
[![Latest Stable Version](https://poser.pugx.org/konnco/laravel-onimage/v/stable)](https://packagist.org/packages/konnco/laravel-onimage)
[![Total Downloads](https://poser.pugx.org/konnco/laravel-onimage/downloads)](https://packagist.org/packages/konnco/laravel-onimage)
[![Latest Unstable Version](https://poser.pugx.org/konnco/laravel-onimage/v/unstable)](https://packagist.org/packages/konnco/laravel-onimage)
[![License](https://poser.pugx.org/konnco/laravel-onimage/license)](https://packagist.org/packages/konnco/laravel-onimage)
[![StyleCI](https://github.styleci.io/repos/228747586/shield?branch=master)](https://github.styleci.io/repos/228747586)

This package is designed to boost up your developing time in managing your image in Laravel framework.

This package based on the famous [Intervention/Image](http://image.intervention.io)

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


## Usage

Add onimage traits into your model

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {
    use \Konnco\Onimage\Onimage;
}
```

## Quick Example
### Uploading Image
```php
$news = News::find(1);
$news->onImageSet('featured', 'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80');

// Or for new instance
$news = new News;
$news->title = "hello world";
$news->save();

$news->onImageSet('featured', 'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80');

// You can pass an array too
$news->onImageSet('featured', [
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80'
]);
```

### Multiple Image
```php

// Pushing into existing attribute
$news = News::find(1);
$news->onImagePush('featured', 'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80');

// You can insert it as an array too
$news->onImagePush('featured', [
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
    'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80']);
```

### Checking is attribute available
```php
$news = News::find(1);
$news->onImageHas('featured');
```

### Getting single image
```php
$news = News::find(1);
$news->onImageFirst('featured', 1);
```

### Getting image collections
```php
$news = News::find(1);
$news->onImageGet('featured');
```

### Deleting Image
```php
$news = News::find(1);
$news->onImageDelete('featured', 1);

// Or delete batch
$news->onImageDelete('featured', [1, 2, 3]);
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
## Contributing
we appreciate all contributions, feel free to write some code or request package.
