<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePhoto extends Model
{
    use HasFactory;
    protected $fillable = ['share_key', 'photo_id', 'view_info', 'download'];

    // public function photo()
    // {
    //     return $this->belongsTo(Photo::class, 'photo_id');
    // }
}
