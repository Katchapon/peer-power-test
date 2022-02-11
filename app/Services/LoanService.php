<?php

namespace App\Services;

use App\Models\Loan;
use App\Repositories\LoanRepository;
use App\Models\RepaymentSchedule;
use App\Repositories\RepaymentScheduleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;

class LoanService
{
    protected $loanRepository;
    protected $repaymentScheduleRepository;

    public function __construct(LoanRepository $loanRepository, RepaymentScheduleRepository $repaymentScheduleRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->repaymentScheduleRepository = $repaymentScheduleRepository;
    }

    public function getAll()
    {
        return $this->loanRepository->getAll();
    }

    public function getById($id)
    {
        return $this->loanRepository->getById($id);
    }

    public function saveLoanData($data)
    {
        $validator = Validator::make($data, [
            'loan_amount' => 'required|numeric|between:1000,100000000',
            'loan_term' => 'required|integer|between:1,50',
            'interest_rate' => 'required|numeric|between:1,36',
            'start_at' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        DB::beginTransaction();

        try {
            $result = $this->loanRepository->save($data);
            $this->createRepaymentSchedules($result);
        } catch (Exception $e) {
            DB::rollBack();

            throw new InvalidArgumentException('Unable to create loan');
        }
        
        DB::commit();

        return $result;
    }

    private function createRepaymentSchedules(Loan $loan)
    {
        $loan->repaymentSchedules()->delete();

        $totalPaymentNo = $loan->getTotalPaymentNo();
        $interestPerYear = $loan->getInterestPerYear();
        $outstandingBalance = round($loan->loan_amount, 2);
        $paidDate = $loan->start_at;

        for ($i = 1; $i<=$totalPaymentNo; $i++) {
            $interest = round(($interestPerYear/12) * $outstandingBalance, 2);
            $principal = round($loan->getPMT() - $interest, 2);
            $outstandingBalance = max(round($outstandingBalance - $principal, 2), 0);

            $this->repaymentScheduleRepository->save($loan, $i, $paidDate, $loan->getPMT(), $principal, $interest, $outstandingBalance);

            $paidDate = $paidDate->addMonth();
        }
    }

    public function updateLoan($data, $id)
    {
        DB::beginTransaction();

        try {
            $loan = $this->loanRepository->update($data, $id);
            $this->createRepaymentSchedules($loan);
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            throw new InvalidArgumentException($e->getMessage());
        }

        DB::commit();

        return $loan;
    }

    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $loan = $this->loanRepository->delete($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete loan data');
        }

        DB::commit();

        return $loan;
    }
}