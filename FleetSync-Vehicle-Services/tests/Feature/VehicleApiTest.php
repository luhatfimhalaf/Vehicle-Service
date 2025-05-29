<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_vehicles()
    {
        Vehicle::factory()->count(3)->create();

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'plate_number',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function test_can_create_vehicle()
    {
        $vehicleData = [
            'type' => 'Car',
            'plate_number' => 'B 1234 ABC',
            'status' => 'Available'
        ];

        $response = $this->postJson('/api/vehicles', $vehicleData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'type',
                    'plate_number',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('vehicles', $vehicleData);
    }

    public function test_can_get_specific_vehicle()
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'type',
                    'plate_number',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_can_update_vehicle()
    {
        $vehicle = Vehicle::factory()->create();
        $updateData = [
            'type' => 'Truck',
            'plate_number' => 'B 5678 XYZ',
            'status' => 'InUse'
        ];

        $response = $this->putJson("/api/vehicles/{$vehicle->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'type',
                    'plate_number',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('vehicles', $updateData);
    }

    public function test_can_delete_vehicle()
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->deleteJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message'
            ]);

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_cannot_create_vehicle_with_duplicate_plate_number()
    {
        $existingVehicle = Vehicle::factory()->create();

        $vehicleData = [
            'type' => 'Car',
            'plate_number' => $existingVehicle->plate_number,
            'status' => 'Available'
        ];

        $response = $this->postJson('/api/vehicles', $vehicleData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['plate_number']);
    }

    public function test_cannot_create_vehicle_with_invalid_status()
    {
        $vehicleData = [
            'type' => 'Car',
            'plate_number' => 'B 1234 ABC',
            'status' => 'InvalidStatus'
        ];

        $response = $this->postJson('/api/vehicles', $vehicleData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
