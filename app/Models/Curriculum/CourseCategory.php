<?php

namespace App\Models\Curriculum;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, mixed $category)
 */
class CourseCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'icon', 'color', 'name', 'slug', 'status', 'serial_number', 'is_featured'];

    public function categoryLang(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function courseInfoList(): HasMany
    {
        return $this->hasMany(CourseInformation::class);
    }
}
