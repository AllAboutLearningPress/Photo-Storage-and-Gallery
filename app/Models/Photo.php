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
        'title', 'size', 'height',
        'width', 'parent_id', 'user_id',
        'file_type', 'file_name', 'should_process',
        'token'
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($photo) {
            // deleting pivot table tag entries before the photo is deleted
            $photo->tags()->detach();
            // add code to delete the file
            // add code to remove download links

        });
    }

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
