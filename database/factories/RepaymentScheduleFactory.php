<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepaymentSchedule>
 */
class RepaymentScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_no' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->dateTime(),
            'payment_amount' => $this->faker->randomFloat(6),
            'principal' => $this->faker->randomFloat(6),
            'interest' => $this->faker->randomFloat(6, 0, 100),
            'balance' => $this->faker->randomFloat(6)
        ];
    }
}
