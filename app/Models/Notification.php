<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'route', 'file_name', 'user_id', 'seen'];

    // the user this notification belongs to
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
