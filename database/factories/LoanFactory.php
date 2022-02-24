<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'loan_amount' => 10000,
            'loan_term' => 12,
            'interest_rate' => 0.1,
            'start_at' => Carbon::createFromDate(2022, 1, 1)
        ];
    }
}
