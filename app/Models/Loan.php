<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public function repaymentSchedules() 
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    public function getPMT()
    {
        $interestPerYear = $this->getInterestPerYear();

        return round((($this->loan_amount * ($interestPerYear/12)) / (1 - pow(1 + ($interestPerYear / 12), -12 * $this->loan_term))), 2);
    }

    public function getTotalPaymentNo()
    {
        return $this->loan_term * 12;
    }

    public function getInterestPerYear()
    {
        return round($this->interest_rate / 100.0, 2);
    }
}
