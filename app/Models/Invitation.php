<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'code', 'invited_by', 'is_accepted', 'role_id'];

    public function invited_by()
    {
        $this->belongsTo(User::class, 'invited_by');
    }
}
