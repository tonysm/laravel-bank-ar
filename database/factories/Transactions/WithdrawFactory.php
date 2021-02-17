<?php

namespace Database\Factories\Transactions;

use App\Models\Transactions\Withdraw;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithdrawFactory extends Factory
{
    protected $model = Withdraw::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
