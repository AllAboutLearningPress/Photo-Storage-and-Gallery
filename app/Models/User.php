<?php

namespace App\Models;

use Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Fortify\TwoFactorAuthenticatable;
// use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    // use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    /**
     * The tags created by this user
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function role()
    {
        return  $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permission)
    {

        $perms  = $this->getCachedPermSlugs();
        return in_array($permission, $perms);
    }

    public function getCachedPermSlugs()
    {

        return Cache::rememberForever('role-perms' . $this->role_id, function () {
            dd($this->role->permissions()->select('slug')->get()->pluck('slug')->toArray());
            return $this->role->permissions()->select('slug')->get()->pluck('slug')->toArray();
        });
    }
}
