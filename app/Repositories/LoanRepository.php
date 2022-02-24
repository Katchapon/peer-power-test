<?php

namespace App\Repositories;

use App\Models\Loan;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class LoanRepository
{
    protected $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function getAll(array $query)
    {
        if (count($query) == 0) {
            return $this->loan->get();
        } else {

            $loan = $this->loan;
            $loan = $loan->whereBetween('loan_amount', [$query['min_loan_amount'], $query['max_loan_amount']]);
            $loan = $loan->whereBetween('loan_term', [$query['min_loan_term'], $query['max_loan_term']]);
            $loan = $loan->whereBetween('interest_rate', [$query['min_interest_rate'], $query['max_interest_rate']]);

            return $loan->get();
        }
    }

    public function getById(int $id)
    {
        return $this->loan
            ->with('repaymentSchedules')
            ->findOrFail($id);
    }

    public function save(array $data)
    {
        $loan = Loan::create($data);

        return $loan;
    }

    public function update(array $data, int $id)
    {
        $loan = $this->loan->findOrFail($id);

        $loan->update($data);

        return $loan;
    }

    public function delete(int $id)
    {
        $loan = $this->getById($id);
        $loan->delete();

        return $loan;
    }
}
