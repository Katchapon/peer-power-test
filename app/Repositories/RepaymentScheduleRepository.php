<?php

namespace App\Repositories;

use App\Models\RepaymentSchedule;
use App\Models\Loan;

class RepaymentScheduleRepository
{
    protected $repaymentSchedule;

    public function __construct(RepaymentSchedule $repaymentSchedule)
    {
        $this->repaymentSchedule = $repaymentSchedule;
    }

    public function save(Loan $loan, $paymentNo, $date, $paymentAmount, $principal, $interest, $balance)
    {
        $repaymentSchedule = new $this->repaymentSchedule;

        $repaymentSchedule->payment_no = $paymentNo;
        $repaymentSchedule->date = $date;
        $repaymentSchedule->payment_amount = $paymentAmount;
        $repaymentSchedule->principal = $principal;
        $repaymentSchedule->interest = $interest;
        $repaymentSchedule->balance = $balance;

        $loan->repaymentSchedules()->save($repaymentSchedule);

        return $repaymentSchedule;
    }
}