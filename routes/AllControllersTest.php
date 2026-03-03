<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Record;
use App\Models\Inventory;
use App\Models\Outcome;
use App\Models\Pricing;
use App\Models\Announcement;
use App\Models\Seat;

class AllControllersTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        return $this->actingAs($user, 'sanctum');
    }

    // --- RecordController Tests ---

    public function test_can_list_records()
    {
        Record::factory()->count(3)->create();

        $this->authenticate()
             ->getJson('/api/records')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_can_create_record()
    {
        $data = [
            'seat' => 'A1',
            'member_ID' => 'MEM001',
            'paid' => true,
            'online' => true,
            'member_amount' => 100,
            'debt' => 0,
        ];

        $this->authenticate()
             ->postJson('/api/records', $data)
             ->assertStatus(201)
             ->assertJsonFragment(['seat' => 'A1']);
    }

    public function test_can_get_top_members()
    {
        Record::factory()->create(['member_ID' => 'TOP1', 'member_amount' => 500]);
        Record::factory()->create(['member_ID' => 'TOP2', 'member_amount' => 100]);

        $this->authenticate()
             ->getJson('/api/top-members')
             ->assertStatus(200)
             ->assertJsonFragment(['member_ID' => 'TOP1']);
    }

    // --- InventoryController Tests ---

    public function test_can_list_inventory()
    {
        Inventory::factory()->count(3)->create();

        $this->authenticate()
             ->getJson('/api/inventories')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_can_create_inventory()
    {
        $data = [
            'type' => 'Drink',
            'item_name' => 'Cola',
            'qty' => 50,
        ];

        $this->authenticate()
             ->postJson('/api/inventories', $data)
             ->assertStatus(201)
             ->assertJsonFragment(['item_name' => 'Cola']);
    }

    public function test_can_update_inventory_quantity()
    {
        $inventory = Inventory::factory()->create(['qty' => 10]);

        $this->authenticate()
             ->postJson("/api/inventories/{$inventory->id}/quantity", [
                 'qty' => 5,
                 'operation' => 'add'
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['qty' => 15]);
    }

    // --- OutcomeController Tests ---

    public function test_can_list_outcomes()
    {
        Outcome::factory()->count(3)->create();

        $this->authenticate()
             ->getJson('/api/outcomes')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_can_create_outcome()
    {
        $data = [
            'description' => 'Electricity Bill',
            'price' => 150.00,
        ];

        $this->authenticate()
             ->postJson('/api/outcomes', $data)
             ->assertStatus(201)
             ->assertJsonFragment(['description' => 'Electricity Bill']);
    }

    public function test_can_get_outcome_total()
    {
        Outcome::factory()->create(['price' => 100]);
        Outcome::factory()->create(['price' => 50]);

        $this->authenticate()
             ->getJson('/api/outcomes/total')
             ->assertStatus(200)
             ->assertJsonFragment(['total' => 150]);
    }

    // --- PricingController Tests ---

    public function test_can_list_pricing_publicly()
    {
        Pricing::factory()->count(3)->create();

        // No authentication needed
        $this->getJson('/api/pricing')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_can_create_pricing_protected()
    {
        $data = [
            'name' => 'VIP Room',
            'hour' => 1,
            'price' => 50,
        ];

        // Unauthenticated
        $this->postJson('/api/pricing', $data)
             ->assertStatus(401);

        // Authenticated
        $this->authenticate()
             ->postJson('/api/pricing', $data)
             ->assertStatus(201)
             ->assertJsonFragment(['name' => 'VIP Room']);
    }

    // --- AnnouncementController Tests ---

    public function test_can_list_announcements_publicly()
    {
        Announcement::factory()->count(3)->create();

        $this->getJson('/api/announcements')
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');
    }

    public function test_can_create_announcement_protected()
    {
        $data = [
            'title' => 'New Event',
            'description' => 'Details here',
            'start_datetime' => now()->addDay()->toDateTimeString(),
            'end_datetime' => now()->addDays(2)->toDateTimeString(),
            'active' => true,
        ];

        $this->authenticate()
             ->postJson('/api/announcements', $data)
             ->assertStatus(201)
             ->assertJsonFragment(['title' => 'New Event']);
    }

    // --- SeatController Tests ---

    public function test_can_list_seats_with_status()
    {
        // Create seats
        $seat1 = Seat::factory()->create(['code' => 'A1']);
        $seat2 = Seat::factory()->create(['code' => 'B1']);

        // Create a record for A1 to make it "online"
        Record::factory()->create([
            'seat' => 'A1',
            'online' => true,
            'created_date' => now(),
        ]);

        $response = $this->getJson('/api/seats');

        $response->assertStatus(200);

        // Check A1 is online
        $response->assertJsonFragment([
            'code' => 'A1',
            'online' => true, // or 1 depending on cast
        ]);

        // Check B1 is offline (default)
        $response->assertJsonFragment([
            'code' => 'B1',
            'online' => false,
        ]);
    }
}
