<?php

namespace App\Http\Controllers\Api;

use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

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

        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->saveLoanData($data);
        } catch (ValidationException $e) {
            $message = $e->getMessage();

            return response()->json(['error' => $message], 500);
        }

        return response()->json($result, $result['status']);
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

        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->updateLoan($data, $id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()()
            ];

        }

        return response()->json($result, $result['status']);
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
}
