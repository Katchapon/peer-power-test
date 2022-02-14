<?php

namespace App\Helpers;

class PMTHelper
{
    public static function calculatePMT($interestRate, $loanAmount, $loanTerm) 
    {
        $interestPerYear = $interestRate / 100;

        return (($loanAmount * ($interestPerYear/12)) / (1 - pow(1 + ($interestPerYear / 12), -12 * $loanTerm)));
    }

    public static function calculateInterest($interestRate, $outstandingBalance) 
    {
        $interestPerYear = $interestRate / 100;

        return ($interestPerYear/12) * $outstandingBalance;
    }

    public static function calculatePrincipal($pmt, $interest)
    {
        return $pmt - $interest;
    }
}