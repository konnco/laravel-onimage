<?php

namespace Konnco\Onimage\Tests\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Konnco\Onimage\Onimage;

class Fruit extends Eloquent
{
    use Onimage;

    protected $imageAttributes = [
        'cover'     => '',
        'galleries' => 'multiple|sizes:original,square|nullable',
    ];
}
