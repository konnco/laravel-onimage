<?php

namespace Konnco\Onimage;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Konnco\Onimage\models\Onimage as OnimageModel;
use Mimey\MimeTypes;

trait Onimage
{
    /**
     * @param null $locale
     *
     * @return mixed
     */
    public function onimagetable()
    {
        return $this->morphMany(OnimageModel::class, 'onimagetable');
    }

    /**
     * Attaching Image
     *
     * Attach image to a model with the setOnImage() method. Accepted Image type :
     * 
     * - string - Path of the image in filesystem.
     * - string - URL of an image (allow_url_fopen must be enabled).
     * - string - Binary image data.
     * - string - Data-URL encoded image data.
     * - string - Base64 encoded image data.
     * - resource - PHP resource of type gd. (when using GD driver)
     * - object - Imagick instance (when using Imagick driver)
     * - object - Intervention\Image\Image instance
     * - object - SplFileInfo instance (To handle Laravel file uploads via Symfony\Component\HttpFoundation\File\UploadedFile)
     * 
     * @param string $attribute attribute field key
     * @param mixed $image Intervention Image
     * @param boolean $stack If stacking setOnimage will be pushed as array
     * 
     * @return void
     */
    public function setOnImage($attribute, $image, $stack = false)
    {
        /*
        |------------------------------------------------------------------------
        | Setup New Image
        |------------------------------------------------------------------------
        | 1. Make image as Image \Intervention\Image\Image
        | 2. Setup file name, extension save path
        | 3. save it into current active storage with file name
        | 4. save it into OnImageModel
        */

        // Step 1  
        $image = Image::make($image);

        // Step 2
        $mimes = new MimeTypes();
        $fileExtension = $mimes->getExtension($image->mime());
        $filename = Str::uuid() . '.' . $fileExtension;
        $savePath = "onimages/" . date('Y-m-d') . '/' . $filename;

        // Step 3
        Storage::disk(config('onimage.driver'))->put($savePath, (string) $image->encode());

        // Step 4
        if ($stack) {
            $model = $this->onimagetable()->find($attribute);
            if ($model == null) {
                $model = new OnimageModel();
            }
        } else {
            $model = new OnimageModel();
        }

        $model->attribute = $attribute;
        $model->path = $savePath;
        $model->width = $image->width();
        $model->height = $image->height();
        $model->save();

        $this->onimagetable()->save($model);
    }

    /**
     * Push image into array attributes
     *
     * @param [type] $attribute
     * @param [type] $image
     * @return void
     */
    public function pushOnImage($attribute, $image)
    {
        return $this->setOnImage($attribute, $image, true);
    }

    /**
     * Get onImage based on galleries
     *
     * @param [type] $attribute
     * @param [type] $size
     * @return void
     */
    public function getOnImage($attribute, $size)
    {
        return $this->onimagetable()->where('attribute', $attribute)->get();
    }

    /**
     * Check if attribute available
     *
     * @param [type] $attribute
     * @return boolean
     */
    public function hasOnImage($attribute)
    {
        return ($this->onimagetable()->where('attribute', $attribute)->count() > 0);
    }

    /**
     * Replacing onimage image
     */
    public function replaceOnImage($attribute, $replacedImage, $image)
    {

    }

    /**
     * Remove OnImage Image
     *
     * @param [type] $attribute
     * @return void
     */
    public function removeOnImage($attribute)
    {

    }

    /**
     * Remove all image
     *
     * @return void
     */
    public function purgeOnImage()
    {
        return $this->onimagetable()->delete();
    }
}
