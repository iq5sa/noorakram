<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonQuiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lesson_id', 'question', 'answers'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function content()
    {
        return $this->belongsTo(LessonContent::class, 'lesson_content_id', 'id');
    }
}
