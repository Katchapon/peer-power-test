<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime:Y-m-d\TH:i:sO'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
