<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Transactions\Deposit|Transactions\Withdraw $transactionable
 */
class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function apply(Account $account): void
    {
        $this->transactionable->apply($account);
    }
}
