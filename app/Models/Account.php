<?php

namespace App\Models;

use App\Models\Transactions\Deposit;
use App\Models\Transactions\TransferDeposit;
use App\Models\Transactions\TransferWithdraw;
use App\Models\Transactions\Withdraw;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposit(int $amount): Transaction
    {
        return DB::transaction(function () use ($amount) {
            return $this->applyTransactionable(Deposit::create([
                'amount' => $amount,
            ]));
        });
    }

    public function withdraw(int $amount): Transaction
    {
        return DB::transaction(function () use ($amount) {
            return $this->applyTransactionable(Withdraw::create([
                'amount' => $amount,
            ]));
        });
    }

    public function transferTo(int $amount, Account $secondAccount): void
    {
        DB::transaction(function () use ($amount, $secondAccount) {
            $transferId = (string) Str::uuid();

            $this->applyTransactionable(TransferWithdraw::create([
                'amount' => $amount,
                'transfer_id' => $transferId,
                'destination_account_id' => $secondAccount->getKey(),
            ]));

            $secondAccount->applyTransactionable(TransferDeposit::create([
                'amount' => $amount,
                'transfer_id' => $transferId,
                'source_account_id' => $this->getKey(),
            ]));
        });
    }

    private function applyTransactionable($transactionable): Transaction
    {
        $transaction = (new Transaction())
            ->transactionable()
            ->associate($transactionable);

        $this->transactions()->save($transaction);

        return tap($transaction)->apply($this);
    }
}
