<?php

namespace Konnco\Onimage;

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
     * Onimage filesystem drivers.
     *
     * @return void
     */
    private function onImageStorage()
    {
        return Storage::disk(config('onimage.driver'));
    }

    /**
     * Attaching Image.
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
     * @param mixed  $image     Intervention Image
     * @param bool   $stack     If stacking setOnimage will be pushed as array
     *
     * @return void
     */
    public function onImageSet($attribute, $images = [], $stack = false)
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

        /**
         * Determine images input is array or string.
         */
        if (!is_array($images)) {
            $images = [$images];
        } else {
            // if images is array we have to force stacks
            // and we have to clear up the old images maybe later
            $stack = true;
            // $this->delete
        }

        foreach ($images as $image) {
            // Step 1
            $image = Image::make($image);

            // Step 2
            $mimes = new MimeTypes();
            $fileExtension = $mimes->getExtension($image->mime());
            $filename = Str::uuid().'.'.$fileExtension;
            $savePath = 'onimages/'.date('Y-m-d').'/'.$filename;

            // Step 3
            $this->onImageStorage()->put($savePath, (string) $image->encode());

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
            $model->name = $filename;
            $model->mime = $image->mime();
            $model->size = $this->geStorage()->size($savePath);
            $model->width = $image->width();
            $model->height = $image->height();
            $model->driver = config('onimage.driver');
            $model->save();

            $this->onimagetable()->save($model);
        }

        return $this->onImageGet($attribute);
    }

    /**
     * Push image into array attributes.
     *
     * @param [type] $attribute
     * @param [type] $image
     *
     * @return void
     */
    public function onImagePush($attribute, $image)
    {
        return $this->onImageSet($attribute, $image, true);
    }

    /**
     * Get onImage based on galleries.
     *
     * @param [type] $attribute
     *
     * @return Konnco\Onimage\models\Onimage
     */
    public function onImageGet($attribute)
    {
        return collect($this->onimagetable ?? [])->where('attribute', $attribute);
    }

    /**
     * get first onimage attribute.
     *
     * @param [type] $attribute
     *
     * @return bool
     */
    public function onImageFirst($attribute)
    {
        return $this->onImageGet($attribute)->first();
    }

    /**
     * Check if attribute available.
     *
     * @param [type] $attribute
     *
     * @return bool
     */
    public function onImageHas($attribute)
    {
        return $this->onimagetable()->where('attribute', $attribute)->count() > 0;
    }

    /**
     * Delete finded attribute with id.
     *
     * @param [type] $attribute
     *
     * @return bool
     */
    public function onImageDelete($attribute, $id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }

        $model = $this->onimagetable()->where('attribute', $attribute)->whereIn('id', $id);

        return $model->delete();
    }

    /**
     * Delete finded attribute with id.
     *
     * @param [type] $attribute
     *
     * @return bool
     */
    public function onImageClear($attribute)
    {
        return $this->onimagetable()->where('attribute', $attribute)->delete();
    }
}
