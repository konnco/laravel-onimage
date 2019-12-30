<?php

namespace Konnco\Onimage\models;

use Illuminate\Database\Eloquent\Model;

class Onimage extends Model
{
    protected $fillable = ['value'];

    /**
     * Get the owning commentable model.
     */
    public function onimagetable()
    {
        return $this->morphTo();
    }
}
