<?php

namespace App\Helpers;

class PMTHelper
{
    public static function calculatePMT(float $interestRate, float $loanAmount, int $loanTerm) 
    {
        return (($loanAmount * ($interestRate/12)) / (1 - pow(1 + ($interestRate / 12), -12 * ($loanTerm / 12))));
    }

    public static function calculateInterest(float $interestRate, float $outstandingBalance) 
    {
        return ($interestRate/12) * $outstandingBalance;
    }

    public static function calculatePrincipal(float $pmt, float $interest)
    {
        return $pmt - $interest;
    }
}