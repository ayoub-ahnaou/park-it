<?php

namespace Tests\Feature;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    // use RefreshDatabase;

    public function test_index()
    {
        $response = $this->get('/api/parkings');
        $response->assertStatus(200);
    }

    public function test_create_new_parking()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/api/parkings', [
            'name' => 'Test Parking',
            'city' => 'Test City',
            'zone' => 'Test zone',
            'places' => 50,
            'price' => 500
        ]);

        $response->assertStatus(201);
    }

    public function test_show_a_parking_by_his_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $parking = Parking::create([
            'name' => 'Test Parking',
            'city' => 'Test City',
            'zone' => 'Test zone',
            'places' => 50,
            'price' => 500
        ]);

        $response = $this->get("/api/parkings/{$parking->name}");
        $response->assertStatus(200);
    }

    public function test_update_a_parking()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $parking = Parking::find(1);

        $response = $this->put("/api/parkings/{$parking->id}", [
            'name' => 'Updated Parking',
            'city' => 'Updated city',
            'zone' => 'Updated zone',
            'places' => 99,
            'price' => 999
        ]);

        $response->assertStatus(404);
    }

    public function test_destroy_a_parking()
    {
        $allparksRes = $this->getJson('api/parkings');

        $allparks = json_decode($allparksRes->getContent(), true);

        $park_id = $allparks["parkings"][0]["id"];

        $response = $this->deleteJson("api/parkings/$park_id");

        $response->assertStatus(401);
    }
}
