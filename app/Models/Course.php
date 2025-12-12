<?php

namespace App\Models;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
