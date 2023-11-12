<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $int)
 */
class PaymentMethods extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "payment_methods";

    // as the timestamps is not needed, so make it false.
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'keyword', 'information', 'status'];
}
