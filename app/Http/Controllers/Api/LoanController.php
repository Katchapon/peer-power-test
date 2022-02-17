<?php

namespace App\Http\Controllers\Api;

use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
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
    public function index()
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->getAll();
        } catch (Exception $e) {
            $message = $e->getMessage();

            return response()->json(['error' => $message], 500);
        }

        return response()->json($result, $result['status']);
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

        $validator = Validator::make($data, [
            'loan_amount' => 'required|numeric|between:1000,100000000',
            'loan_term' => 'required|integer|between:1,50',
            'interest_rate' => 'required|numeric|between:1,36',
            'start_at' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = $this->prepareData($data);

        try {
            $result['data'] = $this->loanService->saveLoanData($data);
        } catch (ValidationException $e) {
            $message = $e->getMessage();

            return response()->json(['error' => $message]);
        }

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
        
        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->getById($id);
        } catch (Exception $e) {
            $message = $e->getMessage();

            return response()->json(['error' => $message], 500);
        }

        return response()->json($result, $result['status']);
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

        $validator = Validator::make($data, [
            'loan_amount' => 'numeric|between:1000,100000000',
            'loan_term' => 'integer|between:1,50',
            'interest_rate' => 'numeric|between:1,36',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = $this->prepareData($data);

        try {
            $result['data'] = $this->loanService->updateLoan($data, $id);
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage()()
            ];

        }

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
        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->deleteById($id);
        } catch (Exception $e) {
            $message = $e->getMessage();

            return response()->json(['error' => $message], 500);
        }

        return response()->json($result, $result['status']);
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
