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

    public function save(Loan $loan, $datas)
    {
        $loan->repaymentSchedules()->createMany($datas);
    }
}