<?php

namespace Database\Factories\Transactions;

use App\Models\Transactions\Deposit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
