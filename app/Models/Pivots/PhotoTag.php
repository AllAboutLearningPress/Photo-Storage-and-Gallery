<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PhotoTag extends Pivot
{
    //
    protected $fillable = ['photo_id', 'tag_id'];
}
