<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LabelPhoto extends Pivot
{

    public $incrementing = true;
    protected $fillable = ['photo_id', 'label_id', 'score'];
}
