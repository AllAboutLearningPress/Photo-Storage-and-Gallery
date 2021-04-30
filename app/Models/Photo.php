<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name', 'size', 'height',
        'width', 'parent_id', 'user_id',
        'file_type'
    ];
    /**
     * The tags that belong to this photo
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->using(PhotoTag::class);
    }

    /*
    * The parent of this photo
    */
    public function parent()
    {
        return $this->hasOne(Photo::class, 'parent_id');
    }
}
