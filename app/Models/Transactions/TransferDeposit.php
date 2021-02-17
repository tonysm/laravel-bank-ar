<?php

namespace App\Models\Transactions;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDeposit extends Model
{
    use HasFactory;

    protected $table = 'transaction_transfer_deposits';

    protected $guarded = [];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function sourceAccount()
    {
        return $this->belongsTo(Account::class);
    }

    public function apply(Account $account)
    {
        $account->increment('amount', $this->amount);
    }
}
