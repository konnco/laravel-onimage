<?php

namespace Konnco\Onimage\Tests;

//use Konnco\Transeloquent\models\Transeloquent;
//use Konnco\Transeloquent\TranseloquentServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path'     => realpath('tests/migrations'),
        ]);
        $this->withFactories(realpath('tests/factories'));
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('onimage', [
            'driver' => 'public',
            'sizes'  => [
                'original' => [null, null],
                'square'   => [600, 600],
            ], ]);

        $app['config']->set('filesystems', ['disks' => [
            'local' => [
                'driver' => 'local',
                'root'   => storage_path('app'),
            ],

            'public' => [
                'driver'     => 'local',
                'root'       => storage_path('app/public'),
                'url'        => env('APP_URL').'/storage',
                'visibility' => 'public',
            ],

            's3' => [
                'driver' => 's3',
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_BUCKET'),
                'url'    => env('AWS_URL'),
            ],

        ]]);
    }

    protected function getPackageProviders($app)
    {
        return [
//            TranseloquentServiceProvider::class,
            \Intervention\Image\ImageServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Image' => \Intervention\Image\Facades\Image::class,
        ];
    }
}
