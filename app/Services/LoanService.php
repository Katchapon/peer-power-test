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
use App\Repositories\UserRepoInterface;

class LoanService
{
    protected $loanRepository;
    protected $repaymentScheduleRepository;

    public function __construct(
        LoanRepository $loanRepository, 
        RepaymentScheduleRepository $repaymentScheduleRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->repaymentScheduleRepository = $repaymentScheduleRepository;
    }

    public function getAll()
    {
        return $this->loanRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->loanRepository->getById($id);
    }

    public function saveLoanData(array $data)
    {

        return DB::transaction(function () use ($data) {

            $result = $this->loanRepository->save($data);

            $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($result);
            $this->repaymentScheduleRepository->save($result, $repaymentSchedules);

            return $result;
        });
    }
    
    public function updateLoan(array $data, int $id)
    {

        return DB::transaction(function () use ($data, $id) {
            $loan = $this->loanRepository->update($data, $id);
            $repaymentSchedules = RepaymentScheduleHelper::generateRepaymentSchedules($loan);

            $loan->repaymentSchedules()->delete();
            $this->repaymentScheduleRepository->save($loan, $repaymentSchedules);

            return $loan;
        });
    }

    public function deleteById(int $id)
    {
        return DB::transaction(function () use ($id) {
            $loan = $this->loanRepository->delete($id);

            return $loan;
        });
    }
}