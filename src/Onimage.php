<?php

namespace Konnco\Onimage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class Onimage extends Model
{
    /**
     * filesystem drivers.
     *
     * @return void
     */
    public function storage()
    {
        return Storage::disk(config('onimage.filesystem'));
    }

    public function moveUp()
    {

    }

    public function moveDown()
    {

    }

    public function moveFirst()
    {

    }

    public function moveLast()
    {

    }

    public function moveTo()
    {

    }

    /**
     * @param $collection
     * @param $content Symfony\Component\HttpFoundation\File\UploadedFile | string
     */
    public function store($collection, $content)
    {

    }
}
