<?php

namespace App\Repositories;

use App\Models\Loan;
use DateTime;
use Illuminate\Support\Carbon;

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
        $loan = Loan::create($data);

        return $loan;
    }

    public function update($data, $id)
    {
        $loan = $this->loan->findOrFail($id);

        $loan->update($data);

        return $loan;
    }

    public function delete($id)
    {
        $loan = $this->getById($id);
        $loan->delete();

        return $loan;
    }
}
