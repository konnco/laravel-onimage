<?php

namespace Konnco\Onimage\controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\Request;
use Image;
use Konnco\Onimage\models\Onimage;

class OnImageController extends Controller
{
    public function onImageCache($width, $height, $filename)
    {
        $model = Onimage::where('name', $filename)->first();
        if ($model == null) {
            abort(404);
        }

        $img = Image::cache(function ($image) use ($width, $height, $model) {
            $storage = Storage::disk($model->driver);
            $image->make($storage->get($model->path))->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
        }, config('konnco.cache_lifetime'), true);
        return $img->stream();
    }
}
