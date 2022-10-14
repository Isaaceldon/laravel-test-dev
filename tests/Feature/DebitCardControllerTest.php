<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCards()
    {
        $debit_card = factory('App\Models\DebitCard')->create();

        $response = $this->get('/debit-cards');
        $response->assertSee($debit_card->type);
    }

    public function testCustomerCannotSeeAListOfDebitCardsOfOtherCustomers()
    {
        // get /debit-cards


    }

    public function testCustomerCanCreateADebitCard()
    {

    $this->actingAs(factory('App\Models\User')->create());

    $debit_card = factory('App\Models\DebitCard')->make();

    $this->post('/debit-cards',$debit_card->toArray());

    $this->assertEquals(1,DebitCard::all()->count());
    }

    public function testCustomerCanSeeASingleDebitCardDetails()
    {
        $debit_card = factory('App\Models\DebitCard')->create();

        $response = $this->get(`/debit-cards/${$debit_card->id}`);
        $response->assertSee($debit_card->type);
    }

    public function testCustomerCannotSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}

        $debit_card = factory('App\Models\DebitCard')->create();

        $response = $this->get(`/debit-cards/${$debit_card->id}`);

        $response->assertSee($debit_card->type)
        ->assertSee($debit_card->number);
    }

    public function testCustomerCanActivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card = factory('App\Models\DebitCard')->create(['user_id' => Auth::id()]);
        $debit_card->disable_at = null;
        $this->put(`/debit-cards/${$debit_card->id}`, $debit_card->toArray());
        $this->assertDatabaseHas('debit_cards',['id'=> $debit_card->id , 'disable_at' => null]);
    }

    public function testCustomerCanDeactivateADebitCard()
    {
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card = factory('App\Models\DebitCard')->create(['user_id' => Auth::id()]);
        $debit_card->disable_at = "25-09-2022";
        $this->put(`/debit-cards/${$debit_card->id}`, $debit_card->toArray());
        $this->assertDatabaseHas('debit_cards',['id'=> $debit_card->id , 'disable_at' => "25-09-2022"]);
    }

    public function testCustomerCannotUpdateADebitCardWithWrongValidation()
    {
        // put api/debit-cards/{debitCard}
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card = factory('App\Model\DebitCard')->create();
        $debit_card->type = "Updated Type";
        $response = $this->put(`/debit-cards/${$debit_card->id}`, $debit_card->toArray());
        $response->assertStatus(403);
    }

    public function testCustomerCanDeleteADebitCard()
    {
        // delete api/debit-cards/{debitCard}
        $this->actingAs(factory('App\Models\User')->create());
        $debit_card = factory('App\Models\DebitCard')->create(['user_id' => Auth::id()]);
        $this->delete(`/debit-cars/${$debit_card->id}`);
        $this->assertDatabaseMissing('debit-cards',['id'=> $debit_card->id]);
    }

    public function testCustomerCannotDeleteADebitCardWithTransaction()
    {
        // delete api/debit-cards/{debitCard}
    }

    // Extra bonus for extra tests :)
}
