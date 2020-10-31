# 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/konnco/laravel-onimage.svg?style=flat-square)](https://packagist.org/packages/konnco/laravel-onimage)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/konnco/laravel-onimage/run-tests?label=tests)](https://github.com/konnco/laravel-onimage/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/konnco/laravel-onimage.svg?style=flat-square)](https://packagist.org/packages/konnco/laravel-onimage)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require konnco/laravel-onimage
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Konnco\Onimage\OnimageServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Konnco\Onimage\OnimageServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$laravel-onimage = new Konnco\Onimage();
echo $laravel-onimage->echoPhrase('Hello, Konnco!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Franky So](https://github.com/FrankySo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
