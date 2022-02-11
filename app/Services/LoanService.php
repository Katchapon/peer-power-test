<?php

namespace App\Services;

use App\Models\Loan;
use App\Repositories\LoanRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\ValidationException;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
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

        $result = $this->loanRepository->save($data);

        return $result;
    }

    public function updateLoan($data, $id)
    {
        DB::beginTransaction();

        try {
            $loan = $this->loanRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update loan');
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