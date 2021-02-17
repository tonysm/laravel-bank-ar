<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transactions\Deposit;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'account_id' => Account::factory(),
            'transactionable' => Deposit::factory()->state(['amount' => 100]),
        ];
    }
}
