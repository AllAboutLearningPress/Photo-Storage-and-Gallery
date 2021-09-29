<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id', 'slug'];

    public static function boot()
    {
        parent::boot();
        // static::created(function ($tag) {
        //     $tag->slug = $tag->generateSlug($tag->name);
        //     $tag->save();
        // });
    }
    /**
     * The photos that belong to this tag
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class)->using(\App\Models\Pivots\PhotoTag::class);
    }
    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function generateSlug($name)
    {
        // removing the file extension Ex: abc.jpeg -> abc
        $name = pathinfo($name, PATHINFO_FILENAME);
        // creating the initial slug
        $slug = Str::slug(strtolower(($name)), '-');
        // if this slug already exists then we will
        // create an incrementing slug
        $last_slug = static::where('slug', '==', $slug)->latest('id')->value('slug');
        if ($last_slug) {

            $slug = $this->incrementSlug($slug);
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
        // // get the slug of the latest created post
        $last_slug = static::where('name', "=", $slug)->latest('id')->skip(1)->value('slug');

        if ($last_slug && is_numeric($last_slug[-1])) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $last_slug);
        }

        return "{$slug}-2";
    }
}
