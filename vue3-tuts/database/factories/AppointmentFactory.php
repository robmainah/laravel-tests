<?php

namespace Database\Factories;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'client_id' => Client::all()->random()->id,
            'description' => fake()->paragraph(),
            'start_time' => $startTime = fake()->dateTimeBetween('-1 year', '+1 year'),
            'end_time' => Carbon::parse($startTime)->addHours(2),
            'status' => rand(1, 3),
        ];
    }
}
