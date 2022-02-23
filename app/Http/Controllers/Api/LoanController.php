<?php

namespace App\Http\Controllers\Api;

use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

class LoanController extends Controller
{

    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $minLoanAmount = $request->query('min_loan_amount', 1000);
        $maxLoanAmount = $request->query('max_loan_amount', 100000000);
        $minLoanTerm = $request->query('min_loan_term', 1);
        $maxLoanTerm = $request->query('max_loan_term', 50);
        $minInterestRate = $request->query('min_interest_rate', 1);
        $maxInterestRate = $request->query('max_interest_rate', 36);

        $query = [
            'min_loan_amount' => $minLoanAmount,
            'max_loan_amount' => $maxLoanAmount,
            'min_loan_term' => $minLoanTerm * 12,
            'max_loan_term' => $maxLoanTerm * 12,
            'min_interest_rate' => $minInterestRate / 100,
            'max_interest_rate' => $maxInterestRate / 100
        ];

        $result['data'] = $this->loanService->getAll($query);

        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'loan_amount',
            'loan_term',
            'interest_rate',
            'start_at'
        ]);

        $request->validate([
            'loan_amount' => 'required|numeric|between:1000,100000000',
            'loan_term' => 'required|integer|between:1,50',
            'interest_rate' => 'required|numeric|between:1,36',
            'start_at' => 'required'
        ]);

        $data = $this->prepareData($data);
        $result['data'] = $this->loanService->saveLoanData($data);

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result['data'] = $this->loanService->getById($id);

        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'loan_amount',
            'loan_term',
            'interest_rate',
            'start_at'
        ]);

        $request->validate([
            'loan_amount' => 'numeric|between:1000,100000000',
            'loan_term' => 'integer|between:1,50',
            'interest_rate' => 'numeric|between:1,36',
        ]);

        $data = $this->prepareData($data);
        $result['data'] = $this->loanService->updateLoan($data, $id);

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result['data'] = $this->loanService->deleteById($id);

        return response()->json($result);
    }

    private function prepareData($data)
    {
        if (Arr::exists($data, 'loan_term'))
        {
            $data['loan_term'] = $data['loan_term'] * 12;
        }

        if (Arr::exists($data, 'interest_rate'))
        {
            $data['interest_rate'] = $data['interest_rate'] / 100;
        }

        if (Arr::exists($data, 'start_at'))
        {
            $data['start_at'] = Carbon::createFromFormat('Y-m-d\TH:i:sO', $data['start_at']);
        }

        return $data;
    }
}
