<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['route', 'file_name', 'user_id', 'seen', 'data'];

    // the user this notification belongs to
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
