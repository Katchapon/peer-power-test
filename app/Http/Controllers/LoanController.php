<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;

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
            $result = [
                'status' => 500,
                'error' => $e->getMessage()()
            ];
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
            'interest_rate'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->loanService->saveLoanData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()()
            ];

            return response()->json($result, $result['status']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    // public function show(Loan $loan)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Loan  $loan
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Loan $loan)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Loan  $loan
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Loan $loan)
    // {
    //     //
    // }
}
