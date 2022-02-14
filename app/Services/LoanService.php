<?php

namespace App\Services;

use App\Models\Loan;
use App\Repositories\LoanRepository;
use App\Repositories\RepaymentScheduleRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;
use App\Helpers\PMTHelper;

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

            throw new InvalidArgumentException($e->getMessage());
        }
        
        DB::commit();

        return $result;
    }

    private function createRepaymentSchedules(Loan $loan)
    {
        $loan->repaymentSchedules()->delete();

        $pmt = PMTHelper::calculatePMT($loan->interest_rate, $loan->loan_amount, $loan->loan_term);
        $totalPaymentNo = $loan->loan_term * 12;
        $outstandingBalance = $loan->loan_amount;
        $paidDate = $loan->start_at;

        for ($i = 1; $i<=$totalPaymentNo; $i++) {
            $interest = PMTHelper::calculateInterest($loan->interest_rate, $outstandingBalance);
            $principal = PMTHelper::calculatePrincipal($pmt, $interest);
            $outstandingBalance = max(($outstandingBalance - $principal), 0);

            $this->repaymentScheduleRepository->save($loan, $i, $paidDate, $pmt, $principal, $interest, $outstandingBalance);

            $paidDate = $paidDate->addMonth();
        }
    }

    public function updateLoan($data, $id)
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
            $loan = $this->loanRepository->update($data, $id);
            $this->createRepaymentSchedules($loan);
        } catch (Exception $e) {
            DB::rollback();

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

            throw new InvalidArgumentException($e->getMessage());
        }

        DB::commit();

        return $loan;
    }
}