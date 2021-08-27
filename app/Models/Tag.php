<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use Str;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];

    public static function boot()
    {
        parent::boot();
        static::created(function ($tag) {
            $tag->slug = $tag->generateSlug($tag->name);
            $tag->save();
        });
    }
    /**
     * The photos that belong to this tag
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class)->using(PhotoTag::class);
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
        $last_slug = static::where('slug', '!=', null)->latest('id')->value('slug');
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

    public function add_temp_url($version, $options = [])
    {

        //$this->src = "/storage/full_size/" . $this->file_name;
        //$this->thumbSrc = "/storage/full_size/" . $this->file_name;
        $this->src = Storage::disk('s3_fullsize')->temporaryUrl(
            $version . "/" . $this->file_name,
            now()->addMinutes(10),
            $options
        );
    }
}
