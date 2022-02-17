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
use App\Helpers\RepaymentScheduleHelper;

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
        DB::beginTransaction();

        try {
            $result = $this->loanRepository->save($data);

            $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($result);
            $this->repaymentScheduleRepository->save($result, $repaymentSchedules);
        } catch (Exception $e) {
            DB::rollBack();

            throw new InvalidArgumentException($e->getMessage());
        }
        
        DB::commit();

        return $result;
    }
    
    public function updateLoan($data, $id)
    {
        DB::beginTransaction();

        try {
            $loan = $this->loanRepository->update($data, $id);
            $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($loan);

            $loan->repaymentSchedules()->delete();
            $this->repaymentScheduleRepository->save($loan, $repaymentSchedules);
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