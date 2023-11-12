<?php

namespace App\Models\HomePage\Fact;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunFactSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'background_image', 'title'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
