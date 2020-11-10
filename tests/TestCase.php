<?php

namespace Konnco\Onimage\Tests;

use Intervention\Image\Facades\Image;
use Intervention\Image\ImageServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath('tests/migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ImageServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Image' => Image::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('onimage', [
            'filesystem' => 'public',
        ]);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('filesystems', ['disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app'),
            ],

            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL') . '/storage',
                'visibility' => 'public',
            ],

            's3' => [
                'driver' => 's3',
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'url' => env('AWS_URL'),
            ],

        ]]);

        include_once __DIR__ . '/../database/migrations/create_laravel_onimage_table.php.stub';
        (new \CreateOnimageTable())->up();
    }
}
