<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['course_information_id', 'title', 'status', 'serial_number', 'duration'];

    public function courseInformation()
    {
        return $this->belongsTo(CourseInformation::class);
    }

    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }
}
