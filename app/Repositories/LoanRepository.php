<?php

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository
{
    protected $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function getAll()
    {
        return $this->loan
            ->get();
    }

    public function save($data)
    {
        $loan = new $this->loan;

        $loan->loan_amount = $data['loan_amount'];
        $loan->loan_term = $data['loan_term'];
        $loan->interest_rate = $data['interest_rate'];

        $loan->save();
    }
}
