<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['Car', 'Truck', 'Van', 'Bus']),
            'plate_number' => strtoupper($this->faker->randomLetter() . ' ' . $this->faker->numberBetween(1000, 9999) . ' ' . $this->faker->randomLetter() . $this->faker->randomLetter() . $this->faker->randomLetter()),
            'status' => $this->faker->randomElement(['Available', 'InUse', 'Maintenance']),
        ];
    }
}
