<?php

namespace App\Models\HomePage\Fact;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountInformation extends Model
{
    use HasFactory;

    protected $table = 'count_informations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'icon', 'color', 'title', 'amount', 'serial_number'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
