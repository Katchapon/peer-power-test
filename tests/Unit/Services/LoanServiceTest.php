<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\LoanService;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use Database\Factories\RepaymentScheduleFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoanServiceTest extends TestCase
{
    use DatabaseMigrations;

    private LoanService $loanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanService = $this->app->make(LoanService::class);
    }

    public function testSaveLoanData()
    {
        $data = [
            'loan_amount' => 10000,
            'loan_term' => 1,
            'interest_rate' => 10.0,
            'start_at' => "2022-02-11T01:45:53+0000"
        ];

        $loan = $this->loanService->saveLoanData($data);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($data['loan_amount'], $loan->loan_amount);
        $this->assertEquals($data['loan_term'], $loan->loan_term);
        $this->assertEquals($data['interest_rate'], $loan->interest_rate);
    }

    public function testGetLoans()
    {
        $loans = Loan::factory()->count(5)->create();

        $result = $this->loanService->getAll();

        $this->assertEquals(count($loans), count($result));
        $this->assertEquals($loans[0]->loan_amount, $result[0]->loan_amount);
    }

    public function testGetLoanById()
    {
        $loan = Loan::factory()
                    ->has(RepaymentSchedule::factory()->count(12))
                    ->create();

        $result = $this->loanService->getById($loan->id);

        $this->assertEquals($loan->loan_amount, $result->loan_amount);
        $this->assertEquals($loan->repaymentSchedules()->count(), $result->repaymentSchedules()->count());
    }

    public function testDeleteLoan()
    {
        $loans = Loan::factory()->count(5)->create();

        $result = $this->loanService->deleteById($loans[0]->id);
        
        $this->assertDatabaseMissing('loans', ['id' => $result->id]);
    }
}