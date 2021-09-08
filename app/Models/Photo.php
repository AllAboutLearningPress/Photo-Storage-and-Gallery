<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Storage;
use Str;

class Photo extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    protected $fillable = [
        'title', 'size', 'height',
        'width', 'parent_id', 'user_id',
        'file_type', 'file_name',
        'sha256', 'dhash'
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($photo) {
            // deleting pivot table tag entries before the photo is deleted
            $photo->tags()->detach();
            $photo->labels()->detach();
            // add code to delete the file
            // add code to remove download links
            Storage::disk('s3_fullsize')->delete([

                'full_size/' . $photo->file_name,
                'thumbnails/' . $photo->file_name,
                'preview_photos/' . $photo->file_name,
            ]);
        });
        static::created(function ($photo) {
            $photo->slug = $photo->generateSlug($photo->title);
            $photo->save();
        });
    }

    /**
     * The user defined tags that belong to this photo
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->using(PhotoTag::class)->withTimestamps();
    }

    /**
     * Labels returned by aws rekognition
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class)->using(LabelPhoto::class)->withPivot('score');
    }

    /*
    * The parent of this photo
    */
    public function parent()
    {
        return $this->hasOne(Photo::class, 'parent_id');
    }

    /**
     * The user that uploaded this photo
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /** Generates path for thumbnails */
    public function genThumbPath()
    {
        return '/thumbnails/' . $this->file_name;
    }
    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function generateSlug($title)
    {
        // removing the file extension Ex: abc.jpeg -> abc
        $title = pathinfo($title, PATHINFO_FILENAME);
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
        $max = static::where('title', '=', $this->title)->latest('id')->skip(1)->value('slug');

        if ($max && is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[1] + 1;
            }, $max);
        }

        return "{$slug}-2";
    }

    public function add_temp_url($file_type, $bucket = null)
    {

        //$this->src = "/storage/full_size/" . $this->file_name;
        //$this->thumbSrc = "/storage/full_size/" . $this->file_name;
        if (!$bucket) {
            $bucket = config('aws.fullsize_bucket');
        }
        $this->src =  (new \App\Utils\AwsS3V4())->presignGet($file_type, $this->file_name, $bucket);
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('labels')->with('tags');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();


        $searchableArray = [
            'title' => $array['title'],
        ];
        $searchableArray['labels'] = $this->labels->map(function ($data) {
            return ['name' => $data['name']];
        });
        $searchableArray['tags'] = $this->tags->map(function ($data) {
            return ['name' => $data['name']];
        });

        if (isset($array['license'])) {
            $searchableArray['license'] = $array['license'];
        }
        return $searchableArray;
    }
}
