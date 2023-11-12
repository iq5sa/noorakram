<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static join(string $string, string $string1, string $string2, string $string3)
 * @method static where(string $string, string $string1, null $string2)
 * @method static findOrFail(mixed $id)
 * @method static find($id)
 */
class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'thumbnail_image',
        'video_link',
        'cover_image',
        'pricing_type',
        'previous_price',
        'current_price',
        'status',
        'is_featured',
        'average_rating',
        'duration',
        'certificate_status',
        'video_watching',
        'quiz_completion',
        'certificate_title',
        'certificate_text',
        'min_quiz_score'
    ];

    public function information(): HasMany
    {
        return $this->hasMany(CourseInformation::class);
    }

    public function faq(): HasMany
    {
        return $this->hasMany(CourseFaq::class);
    }

    public function enrolment(): HasMany
    {
        return $this->hasMany(CourseEnrolment::class, 'course_id', 'id');
    }

    public function review(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'course_id', 'id');
    }

    public function quizScore(): HasMany
    {
        return $this->hasMany(QuizScore::class, 'course_id', 'id');
    }
}
