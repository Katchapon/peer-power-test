<?php

namespace App\Helpers;

class PMTHelper
{
    public static function calculatePMT($interestRate, $loanAmount, $loanTerm) 
    {
        return (($loanAmount * ($interestRate/12)) / (1 - pow(1 + ($interestRate / 12), -12 * $loanTerm)));
    }

    public static function calculateInterest($interestRate, $outstandingBalance) 
    {
        return ($interestRate/12) * $outstandingBalance;
    }

    public static function calculatePrincipal($pmt, $interest)
    {
        return $pmt - $interest;
    }
}