<?php

namespace App\Models;

use App\Models\Pivots\PermissionRole;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'slug'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->using(PermissionRole::class)->withTimestamps();
    }

    public function removeCachedPermSlugs()
    {
        Cache::forget($this->getCacheKey());
    }

    public function cachePermSlugs()
    {
        Cache::forever(
            $this->getCacheKey(),
            $this->permissions()->select('slug')->get()->pluck('slug')->toArray()
        );
    }

    public function getCacheKey()
    {
        return 'role-perms' . $this->id;
    }
}
