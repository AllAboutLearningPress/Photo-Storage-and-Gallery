<?php

namespace App\Models;

use App\Events\PhotoCreating;
use App\Events\PhotoDeleting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

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
        static::created(function ($photo) {
            $photo->slug = $photo->generateSlug($photo->title);
            $photo->save();
        });
    }

    /**
     * The tags that belong to this photo
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->using(PhotoTag::class)->withTimestamps();
    }

    /*
    * The parent of this photo
    */
    public function parent()
    {
        return $this->hasOne(Photo::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function generateSlug($title)
    {
        // removing the file extension Ex: abc.jpeg -> abc
        $title = substr($title, 0, (strrpos($title, ".")));
        // creating the initial slug
        $slug = Str::slug($title, '-');
        // if this slug already exists then we will
        // create an incrementing slug
        $priv_slug = static::where('slug', '=', $slug)->value('slug');
        if ($priv_slug) {
            $slug = $this->incrementSlug($priv_slug);
        }

        return $slug;
    }

    /* Increment slug
     *
     * @param   string $slug
     * @return  string
     **/
    public function incrementSlug($slug)
    {
        // get the slug of the latest created post
        $max = static::whereTitle($this->title)->latest('id')->skip(1)->value('slug');

        if ($max && is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $max);
        }

        return "{$slug}-2";
    }
}
