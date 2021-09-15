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

    public function genFullPath($file_version, $starting_slash = false)
    {
        # removing first and last slash if any
        #$file_version = preg_replace('/^\/|\/$/g', '', $file_version);
        if (!($file_version[-1] == '/')) {
            # forward slash not found on the end of $file_version
            $file_version .= '/';
        }
        if ($starting_slash && $file_version[0] != '/') {
            $file_version = '/' . $file_version;
        }
        // dd($file_version);
        return $file_version . $this->file_name;
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

    public function addTempUrl($file_version, $bucket = null)
    {


        if (!$bucket) {
            $bucket = config('aws.fullsize_bucket');
        }
        $this->src =  (new \App\Utils\AwsS3V4())->presignGet($this->genFullPath($file_version), $bucket);
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
