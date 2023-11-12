<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_categories_section_status',
        'call_to_action_section_status',
        'featured_courses_section_status',
        'features_section_status',
        'video_section_status',
        'fun_facts_section_status',
        'testimonials_section_status',
        'featured_instructors_section_status',
        'about_us_section_status',
        'latest_blog_section_status',
        'footer_section_status'
    ];
}
