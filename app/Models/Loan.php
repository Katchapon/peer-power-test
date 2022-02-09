<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    function RepaymentSchedules() {
        return $this->hasMany('App\Model\RepaymentSchedule');
    }
}