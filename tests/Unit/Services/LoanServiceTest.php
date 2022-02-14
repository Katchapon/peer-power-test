<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\LoanService;
use App\Models\Loan;
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
}