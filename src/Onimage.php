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
     * defined image, use
     * protected $imageAttributes.
     */
    protected $onimage = [
        'attributes' => [],
        'modified'   => [],
    ];

    /**
     * Booting process to registering eloquent events.
     */
    public static function bootOnimage(): void
    {
        static::saving(function (Model $model) {
            $model->onimageSavingObserver();
        });

        static::saved(function (Model $model) {
            $model->onimageSavedObserver();
        });

//        static::deleting(function (Model $model) {
//            return $model->deleteTranslations();
//        });
//
//        static::retrieved(function (Model $model) {
//            $model->getTranslations();
//            $model->getAvailableTranslations();
//        });
    }

    /**
     * Saving Image to local.
     */
    private function onimageSavedObserver()
    {
        foreach ($this->onimage['modified'] as $image) {
            if (count($image['files']) === 0) {
                continue;
            }

            foreach ($image['files'] as $file) {
                $interventionImage = Image::make($file);
                $this->onimageSave($image['attribute'], $interventionImage, $image['size']);
            }
        }
    }

    private function onimageSave($attribute, \Intervention\Image\Image $image, $sizes = ['original'])
    {
        $image->backup();
        $mimes = new MimeTypes();
        $fileExtension = $mimes->getExtension($image->mime());
        $filename = Str::uuid().'.'.$fileExtension;
        foreach ($sizes as $size) {
            $savePath = "images/$size/".date('Y/m/d').'/'.$filename;
            // Checking Configuration
            $sizeCheck = config('onimage.sizes.'.$size, null);
            if ($sizeCheck == null) {
                throw new \Exception($size.' is not a valid image size');
            }

            $width = config("onimage.sizes.$size.0", null);
            $height = config("onimage.sizes.$size.1", null);
            $position = config("onimage.sizes.$size.2", null);

            if ($width != null && $width != null) {
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                }, $position);
            }

            Storage::disk(config('onimage.driver'))->put($savePath, $image->__toString());

            // save on databases
            $model = new OnimageModel();
            $model->attribute = $attribute;
            $model->size = $size;
            $model->path = $savePath;
            $model->width = $image->width();
            $model->height = $image->height();
            $model->save();

            $this->onimagetable()->save($model);

            $image->reset();
        }

        return true;
    }

    /**
     * Saving Image to local.
     *
     * move all attributes into temporary protected variables
     */
    private function onimageSavingObserver()
    {
        $attributes = collect($this->attributes);
        $imageAttributes = collect($this->imageAttributes ?? []);

        $imageAttributes->each(function ($image, $key) use (&$attributes) {
            $defaultConfig = [
                'nullable' => false,
                'multiple' => false,
            ];

            $config = collect(explode('|', $image));
            $config->each(function ($configItem) use (&$attributes, &$defaultConfig) {
                if (strpos($configItem, 'sizes') !== false) {
                    $sizeList = explode(':', $configItem)[1];
                    $sizeArray = explode(',', $sizeList);
                    $defaultConfig['size'] = $sizeArray;
                } elseif (strpos($configItem, 'multiple') !== false) {
                    $defaultConfig['multiple'] = true;
                } elseif (strpos($configItem, 'nullable') !== false) {
                    $defaultConfig['nullable'] = true;
                }

                $defaultConfig['size'] = array_unique(array_merge($defaultConfig['size'] ?? [], ['original']));
            });

            $defaultConfig['files'] = $attributes[$key] ?? null;
            $defaultConfig['attribute'] = $key;

            // convert into array
            if (is_array($defaultConfig['files']) == false) {
                $defaultConfig['files'] = [$defaultConfig['files']];
            }

            // removing empty file
            $defaultConfig['files'] = array_filter($defaultConfig['files'], 'strlen');

            if ($defaultConfig['nullable'] === false && count($defaultConfig['files']) == 0) {
                throw new \Exception($key.' attribute is null, define on your configuration nullable into your configuration.');
            }

            $this->onimage['modified'][$key] = $defaultConfig;
            $attributes = $attributes->forget($key);
        });

        $this->attributes = $attributes->toArray();
    }

    /**
     * @param $attribute string field attribute
     * @param $size string default original
     */
    public function onimage($attribute, $size = 'original')
    {
        $imageAttributes = $this->imageAttributes ?? [];
        if (array_key_exists($attribute, $imageAttributes) == false) {
            throw new \Exception($attribute.' Attribute not found');
        }

        $driver = config('onimage.driver');
        $url = config('filesystems.disks.'.$driver.'.url');
        $images = $this->onimagetable()->where('attribute', $attribute)->where('size', $size);

        $responseImage = [];

        if (strpos($this->imageAttributes[$attribute], 'multiple') !== false) {
            foreach ($images->get() as $image) {
                $responseImage[$image->id] = $url.'/'.$image->path;
            }
        } else {
            $responseImage = $url.'/'.$images->first()->path;
        }

        return $responseImage;
    }

    /**
     * Override parents functions to get single attributes.
     *
     * @param $key
     *
     * @return mixed
     */
//    public function getAttribute($key)
//    {
//        // checking attribute
//        $imageAttributes = collect($this->imageAttributes ?? []);
//        if ($imageAttributes->get($key, null) == null) {
//            return parent::getAttribute($key);
//        }
//
//        $images = $this->onimagetable()->where('attribute', $key);
//
//        $driver = config('onimage.driver');
//        dd('filesystems.disks.'.$driver.".url");
//        $url = config('filesystems.disks.'.$driver.".url");
//        dd($url);
//        $response = [];
//
//        // check if type is multiple
//        if (strpos($this->imageAttributes[$key], "multiple")) {
//            foreach ($images as $image) {
//                $response[] = $image->path;
//            }
//        }

//        $defaultLocale = $this->getDefaultLocale();
//        $currentLocale = $this->getCurrentLocale();
//
//        if ($defaultLocale == $currentLocale) {
//            return parent::getAttribute($key);
//        } else {
//            return @$this->transeloquent['attributes'][$key] ?? parent::getAttribute($key);
//        }
//    }

    /**
     * Saving Translation.
     *
     * @return bool
     */
    public function saveTranslations()
    {
        $defaultLocale = $this->getDefaultLocale();
        $currentLocale = $this->getCurrentLocale();

        if ($defaultLocale != $currentLocale) {
            $attributes = isset($this->translateOnly) ? $this->getTranslateOnlyAttributes() : $this->getTranslateExcept();

            foreach ($attributes as $key => $attribute) {
                if ($attribute != null) {
                    $translate = $this->transeloquent($this->getCurrentLocale())->where('key', $key)->first();
                    if ($translate == null) {
                        $transeloquentModel = $this->getTranseloquentModel();
                        $translate = new $transeloquentModel();
                    }
                    $translate->locale = $this->getCurrentLocale();
                    $translate->key = $key;
                    $translate->value = $attribute;
                    $translate->save();
                    $this->transeloquent($this->getCurrentLocale())->save($translate);
                }
            }

            $this->setRawTranslatedAttributes($attributes);
            $this->setRawAttributes($this->getOriginal());
        }

        return true;
    }

    /**
     * Delete Translation.
     *
     * @return mixed
     */
    public function deleteTranslations()
    {
        if (!$this->isSoftDelete()) {
            return $this->transeloquent()->delete();
        }
    }

    /*
     * Checking Model is softdeleting or not.
     *
     * @return bool
     */
//    public function isSoftDelete()
//    {
//        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this)) && !$this->forceDeleting;
//    }
}
