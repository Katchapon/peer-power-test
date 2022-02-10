<?php

namespace App\Services;

use App\Models\Loan;
use App\Repositories\LoanRepository;

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

    public function saveLoanData($data)
    {
        $result = $this->loanRepository->save($data);

        return $result;
    }
}