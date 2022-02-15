<?php

namespace Tests\Unit\Helpers;

use App\Helpers\RepaymentScheduleHelper;
use Tests\TestCase;
use App\Models\Loan;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RepaymentScheduleTest extends TestCase
{
    use DatabaseMigrations;

    public function testGenerateRepaymentSchedules()
    {
        $loan = Loan::factory()->create();

        $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($loan);

        $this->assertEquals($loan->loan_term * 12, count($repaymentSchedules));
    }
}