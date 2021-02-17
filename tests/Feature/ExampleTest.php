<?php

namespace Tests\Feature;

use App\Exceptions\CannotWithdrawException;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeposit()
    {
        /** @var Account $account */
        $account = Account::factory()->create([
            'amount' => 100,
        ]);

        $account->deposit(100);

        $this->assertEquals(200, $account->amount);
        $this->assertCount(1, $account->transactions);
        $this->assertEquals(100, $account->transactions->first()->transactionable->amount);
    }

    public function testCanWithdraw()
    {
        /** @var Account $account */
        $account = Account::factory()->create([
            'amount' => 100,
        ]);

        $account->withdraw(50);

        $this->assertEquals(50, $account->amount);
        $this->assertCount(1, $account->transactions);
        $this->assertEquals(50, $account->transactions->first()->transactionable->amount);
    }

    public function testCannotWithdrawWithInsufficientBalance()
    {
        /** @var Account $account */
        $account = Account::factory()->create([
            'amount' => 100,
        ]);

        try {
            $account->withdraw(150);

            $this->fail('CannotWithdrawException should have been thrown.');
        } catch (CannotWithdrawException $e) {
        }

        $this->assertEquals(100, $account->amount);
        $this->assertCount(0, $account->transactions);
    }

    public function testCanTransferToAnotherAccount()
    {
        /** @var Account $account */
        $account = Account::factory()->create([
            'amount' => 100,
        ]);

        /** @var Account $secondAccount */
        $secondAccount = Account::factory()->create([
            'amount' => 100,
        ]);

        $account->transferTo(50, $secondAccount);

        // Their balances are updated.
        $this->assertEquals(50, $account->amount);
        $this->assertEquals(150, $secondAccount->amount);

        // Both accounts have 1 transaction each.
        $this->assertCount(1, $account->transactions);
        $this->assertCount(1, $secondAccount->transactions);

        // Both transfers share the same amount.
        $this->assertEquals(50, $account->transactions->first()->transactionable->amount);
        $this->assertEquals(50, $secondAccount->transactions->first()->transactionable->amount);

        // Both transfers point to each other as source/destination accounts.
        $this->assertTrue($account->transactions->first()->transactionable->destinationAccount->is($secondAccount));
        $this->assertTrue($secondAccount->transactions->first()->transactionable->sourceAccount->is($account));

        // Both transfers share the same transfer_id.
        $this->assertNotNull($account->transactions->first()->transactionable->transfer_id);
        $this->assertEquals(
            $account->transactions->first()->transactionable->transfer_id,
            $secondAccount->transactions->first()->transactionable->transfer_id
        );
    }

    public function testCannotTransferWithInsufficientBalance()
    {
        /** @var Account $account */
        $account = Account::factory()->create([
            'amount' => 100,
        ]);

        /** @var Account $secondAccount */
        $secondAccount = Account::factory()->create([
            'amount' => 100,
        ]);

        try {
            $account->transferTo(200, $secondAccount);

            $this->fail('CannotWithdrawException should have been thrown.');
        } catch (CannotWithdrawException $e) {
        }

        $this->assertEquals(100, $account->amount);
        $this->assertEquals(100, $secondAccount->amount);
        $this->assertCount(0, $account->transactions);
        $this->assertCount(0, $secondAccount->transactions);
    }
}
