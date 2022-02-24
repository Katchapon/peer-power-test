<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_no', 'date', 'payment_amount', 'principal', 'interest', 'balance'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
