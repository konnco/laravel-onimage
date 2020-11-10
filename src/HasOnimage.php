<?php

namespace Konnco\Onimage;

trait HasOnimage
{
    public function onimage()
    {
        return $this->morphOne(Onimage::class, 'onimagetable');
    }

    public function addOnimage()
    {
    }

    public function getOnimage()
    {
    }

    public function clearOnimage()
    {
    }
}
