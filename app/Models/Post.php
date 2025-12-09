<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title_ar', 'title_en', 'body_ar', 'body_en', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
