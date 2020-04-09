<?php

namespace Konnco\Onimage\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Konnco\Onimage\Tests\models\Fruit;

class OnimageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testSingleUpload()
    {
        $fruit = new Fruit();
        $fruit->name = 'Single Upload';
        $fruit->save();

        $fruit->onImageSet('featured', 'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80');
        static::assertEquals(1, $fruit->onImageGet('featured')->count());

        return $fruit;
    }

    /** @test */
    public function testMultipleUpload()
    {
        $fruit = new Fruit();
        $fruit->name = 'Multiple Upload';
        $fruit->save();

        $fruit->onImageSet('galleries', [
            'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
            'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
            'https://images.unsplash.com/photo-1562887250-9a52d844ad30?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2089&q=80',
        ]);
        static::assertEquals(3, $fruit->onImageGet('galleries')->count());

        return $fruit;
    }

    /** @test */
    public function testPushSingleUpload(): void
    {
        $fruit = $this->testSingleUpload();
        $fruit->onImagePush('featured', 'https://images.unsplash.com/photo-1586132415792-380e8a1ee736?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80');
        static::assertEquals(2, $fruit->onImageGet('featured')->count());
    }

    /** @test */
    public function testPushMultipleUpload(): void
    {
        $fruit = $this->testSingleUpload();
        $fruit->onImagePush('featured', [
            'https://images.unsplash.com/photo-1586132415792-380e8a1ee736?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
            'https://images.unsplash.com/photo-1586132415792-380e8a1ee736?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
            'https://images.unsplash.com/photo-1586132415792-380e8a1ee736?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
        ]);
        static::assertEquals(4, $fruit->onImageGet('featured')->count());
    }

    /** @test */
    public function testHasOnImageTrue(): void
    {
        $fruit = $this->testSingleUpload();
        static::assertEquals(true, $fruit->onImageHas('featured'));
    }

    /** @test */
    public function testHasOnImageFalse(): void
    {
        $fruit = new Fruit();
        $fruit->name = 'Multiple Upload';
        $fruit->save();

        static::assertEquals(false, $fruit->onImageHas('featured'));
    }

    /** @test */
    public function testFirstOnimage(): void
    {
        $fruit = $this->testMultipleUpload();
        static::assertEquals(true, $fruit->onImageFirst('galleries') !== null);
    }

    /** @test */
    public function testDeleteSingle(): void
    {
        $fruit = $this->testMultipleUpload();
        $fruit->onImageDelete('galleries', 1);

        static::assertEquals(true, $fruit->onImageGet('galleries')->find(1) == null);
        static::assertEquals(2, $fruit->onImageGet('galleries')->count());
    }

    /** @test */
    public function testDeleteMultiple(): void
    {
        $fruit = $this->testMultipleUpload();
        $fruit->onImageDelete('galleries', [1, 2]);

        static::assertEquals(0, $fruit->onImageGet('galleries')->find([1, 2])->count());
        static::assertEquals(1, $fruit->onImageGet('galleries')->count());
    }

    /** @test */
    public function testDeleteClear(): void
    {
        $fruit = $this->testMultipleUpload();
        $fruit->onImageClear('galleries');

        static::assertEquals(false, $fruit->onImageHas('galleries'));
    }

    /** @test */
    public function testGenerateUrl(): void
    {
        $fruit = $this->testMultipleUpload();
        dd($fruit->onImageFirst('galleries')->url(300, 300));

        static::assertEquals(false, $fruit->onImageHas('galleries'));
    }
}
