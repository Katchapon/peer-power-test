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

    public function getById($id)
    {
        return $this->loan
            ->with('repaymentSchedules')
            ->findOrFail($id);
    }

    public function save($data)
    {
        $loan = new $this->loan;

        $loan->loan_amount = $data['loan_amount'];
        $loan->loan_term = $data['loan_term'];
        $loan->interest_rate = $data['interest_rate'];

        $loan->save();

        return $loan;
    }

    public function update($data, $id)
    {
        $loan = $this->getById($id);

        $loan->loan_amount = $data['loan_amount'];
        $loan->loan_term = $data['loan_term'];
        $loan->interest_rate = $data['interest_rate'];

        $loan->save();

        return $loan;
    }

    public function delete($id)
    {
        $loan = $this->getById($id);
        $loan->delete();

        return $loan;
    }
}
