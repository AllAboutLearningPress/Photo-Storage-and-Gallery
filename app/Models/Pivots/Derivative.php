<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Derivative extends Pivot
{
    protected $fillable = ['parent_id', 'derived_id', 'reason'];
}
