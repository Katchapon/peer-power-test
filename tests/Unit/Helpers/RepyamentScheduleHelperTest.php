<?php

namespace Tests\Unit\Helpers;

use App\Helpers\RepaymentScheduleHelper;
use Tests\TestCase;
use App\Models\Loan;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RepaymentScheduleTest extends TestCase
{
    use DatabaseMigrations;

    public function test_generate_repayment_schedules()
    {
        $loan = Loan::factory()->create();

        $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($loan);

        $this->assertEquals($loan->loan_term, count($repaymentSchedules));
        $this->assertEquals(879.16, round($repaymentSchedules[0]['payment_amount'], 2));
        $this->assertEquals(0, $repaymentSchedules[11]['balance']);
        $this->assertEquals(12, $repaymentSchedules[11]['payment_no']);
    }
}