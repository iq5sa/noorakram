<?php

namespace App\Models\Curriculum;

use App\Models\LessonComplete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'title', 'status', 'serial_number', 'duration', 'completion_status'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function content()
    {
        return $this->hasMany(LessonContent::class);
    }

    public function quiz()
    {
        return $this->hasMany(LessonQuiz::class);
    }

    public function lesson_complete()
    {
        return $this->hasMany(LessonComplete::class, 'lesson_id', 'id');
    }
}
