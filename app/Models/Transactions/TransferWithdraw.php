<?php

namespace App\Models\Transactions;

use App\Exceptions\CannotWithdrawException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferWithdraw extends Model
{
    use HasFactory;

    protected $table = 'transaction_transfer_withdraws';

    protected $guarded = [];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(Account::class);
    }

    public function apply(Account $account)
    {
        if ($account->amount < $this->amount) {
            throw new CannotWithdrawException();
        }

        $account->decrement('amount', $this->amount);
    }
}
