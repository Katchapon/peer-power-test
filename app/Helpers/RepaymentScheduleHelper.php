<?php

namespace App\Helpers;

use App\Models\Loan;
use App\Models\RepaymentSchedule;
use App\Helpers\PMTHelper;

class RepaymentScheduleHelper
{
    public static function generateRepaymentSchedules(Loan $loan)
    {
        $pmt = PMTHelper::calculatePMT($loan->interest_rate, $loan->loan_amount, $loan->loan_term);
        $totalPaymentNo = $loan->loan_term * 12;
        $outstandingBalance = $loan->loan_amount;
        $results = [];

        for ($i = 1; $i<=$totalPaymentNo; $i++) {
            $interest = PMTHelper::calculateInterest($loan->interest_rate, $outstandingBalance);
            $principal = PMTHelper::calculatePrincipal($pmt, $interest);
            $outstandingBalance = max(($outstandingBalance - $principal), 0);
            $paidDate = $loan->start_at->addMonth($i-1)->copy();

            $results[] = [
                'payment_no' => $i,
                'date' => $paidDate,
                'payment_amount' => $pmt,
                'principal' => $principal,
                'interest' => $interest,
                'balance' => $outstandingBalance
            ];
        }

        return $results;
    }
}