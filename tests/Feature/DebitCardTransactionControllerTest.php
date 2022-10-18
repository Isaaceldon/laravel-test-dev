<?php

namespace Tests\Feature;

use App\Models\DebitCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DebitCard $debitCard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->debitCard = DebitCard::factory()->create([
            'user_id' => $this->user->id
        ]);
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCardTransactions()
    {
        // get /debit-card-transactions
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card_transactions = factory('App\Models\DebitCardTransaction')->make();

        $response = $this->get('/debit-cards-transactions');
        $response->assertSee($debit_card_transaction->amount);
    }

    public function testCustomerCannotSeeAListOfDebitCardTransactionsOfOtherCustomerDebitCard()
    {
        // get /debit-card-transactions
    }

    public function testCustomerCanCreateADebitCardTransaction()
    {
        // post /debit-card-transactions
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card_transactions = factory('App\Models\DebitCardTransaction')->make();

        $this->post('/debit-card-transaction',$debit_card_transactions->toArray());

        $this->assertEquals(1,DebitCardTransaction::all()->count());
    }

    public function testCustomerCannotCreateADebitCardTransactionToOtherCustomerDebitCard()
    {
        // post /debit-card-transactions
    }

    public function testCustomerCanSeeADebitCardTransaction()
    {
        // get /debit-card-transactions/{debitCardTransaction}

        $this->actingAs(factory('App\Models\User')->create());
        $debit_card_transactions = factory('App\Models\DebitCardTransaction')->make();

        $response = $this->get('/debit-card-transactions/{debitCardTransaction}');
        $response->assertSee($debit_card_transaction->amount);
    }

    public function testCustomerCannotSeeADebitCardTransactionAttachedToOtherCustomerDebitCard()
    {
        // get /debit-card-transactions/{debitCardTransaction}
    }

    // Extra bonus for extra tests :)
}
