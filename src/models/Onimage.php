<?php

namespace Konnco\Onimage\models;

use Illuminate\Database\Eloquent\Model;

class Onimage extends Model
{
    protected $with = ['onimagetable'];

    /**
     * Get the owning commentable model.
     */
    public function onimagetable()
    {
        return $this->morphTo();
    }

    public function url($width=null, $height=null)
    {
        if($width){
            $width = "width=".$width;
        }

        if($height){
            $height = "height=".$width;
        }

        $query = [];

        return url("/oc/media/{$this->name}?".implode("&",$query));
    }
}
