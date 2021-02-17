<?php

namespace App\Models\Transactions;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'transaction_deposits';

    protected $guarded = [];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function apply(Account $account)
    {
        $account->increment('amount', $this->amount);
    }
}
