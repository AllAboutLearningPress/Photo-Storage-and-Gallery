<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePhoto extends Model
{
    use HasFactory;
    protected $fillable = ['share_key', 'photo_id', 'view_info', 'download'];


    public function genUrl()
    {
        return route('share.show', ['key' => $this->share_key]);
    }

    public function photo()
    {
        return $this->belongsTo(\App\Models\Photo::class, 'photo_id');
    }
}
