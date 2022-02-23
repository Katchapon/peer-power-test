<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use App\Services\LoanService;
use App\Repositories\LoanRepository;
use App\Repositories\RepaymentScheduleRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Support\Carbon;

class LoanServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_save_loan_data() {
        $data = [
            'loan_amount' => 10000,
            'loan_term' => 12,
            'interest_rate' => 0.1,
            'start_at' => Carbon::createFromDate(2022, 1, 1)
        ];

        $mockedLoanRepo = Mockery::mock(LoanRepository::class, function (MockInterface $mock) {
            $mockLoan = Loan::factory()->make();
            $mock->shouldReceive('save')->andReturn($mockLoan);
        });

        $mockedRepaymentScheduleRepo = Mockery::mock(RepaymentScheduleRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('save')->once();
        });
        
        $this->app->instance(LoanRepository::class, $mockedLoanRepo);
        $this->app->instance(RepaymentScheduleRepository::class, $mockedRepaymentScheduleRepo);

        $service = $this->app->make(LoanService::class);

        $loan = $service->saveLoanData($data);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($data['loan_amount'], $loan->loan_amount);
        $this->assertEquals($data['loan_term'], $loan->loan_term);
        $this->assertEquals($data['interest_rate'], $loan->interest_rate);
    }

    public function test_get_loans() {
        $mockedLoanRepo = Mockery::mock(LoanRepository::class, function (MockInterface $mock) {
            $mockLoan = Loan::factory()->count(5)->make();
            $mock->shouldReceive('getAll')->andReturn($mockLoan);
        });

        $this->app->instance(LoanRepository::class, $mockedLoanRepo);

        $service = $this->app->make(LoanService::class);
        
        $loans = $service->getAll();

        $this->assertEquals(5, count($loans));
        $this->assertEquals(10000, $loans[0]->loan_amount);
    }

    public function test_get_loan_by_id() {
        $mockedLoanRepo = Mockery::mock(LoanRepository::class, function (MockInterface $mock) {
            $mockLoan = Loan::factory()
                        ->has(RepaymentSchedule::factory()->count(12))
                        ->make();

            $mock->shouldReceive('getById')->andReturn($mockLoan);
        });

        $this->app->instance(LoanRepository::class, $mockedLoanRepo);

        $service = $this->app->make(LoanService::class);
        
        $loan = $service->getById(1);

        $this->assertEquals(10000, $loan->loan_amount);
    }

    public function test_delete_loan() {
        $mockedLoanRepo = Mockery::mock(LoanRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')->once();
        });

        $this->app->instance(LoanRepository::class, $mockedLoanRepo);

        $service = $this->app->make(LoanService::class);

        $service->deleteById(1);
    }
}
