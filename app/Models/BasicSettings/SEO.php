<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;

    protected $table = 'seos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'meta_keyword_home',
        'meta_description_home',
        'meta_keyword_courses',
        'meta_description_courses',
        'meta_keyword_instructors',
        'meta_description_instructors',
        'meta_keyword_blog',
        'meta_description_blog',
        'meta_keyword_faq',
        'meta_description_faq',
        'meta_keyword_contact',
        'meta_description_contact',
        'meta_keyword_login',
        'meta_description_login',
        'meta_keyword_signup',
        'meta_description_signup',
        'meta_keyword_forget_password',
        'meta_description_forget_password',
        'meta_keyword_about',
        'meta_description_about'
    ];

    public function seoLang()
    {
        return $this->belongsTo(Language::class);
    }
}
