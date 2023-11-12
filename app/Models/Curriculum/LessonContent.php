<?php

namespace App\Models\Curriculum;

use App\Models\LessonContentComplete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonContent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lesson_id',
        'video_unique_name',
        'video_original_name',
        'video_duration',
        'file_unique_name',
        'file_original_name',
        'text',
        'code',
        'type',
        'order_no',
        'completion_status'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quiz()
    {
        return $this->hasMany(LessonQuiz::class, 'lesson_content_id', 'id');
    }

    public function lesson_content_complete()
    {
        return $this->hasMany(LessonContentComplete::class, 'lesson_content_id', 'id');
    }
}
