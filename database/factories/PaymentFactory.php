<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'recorded_by' => User::factory()->create(['role' => 'treasurer'])->id,
            'amount' => fake()->randomFloat(2, 500, 5000),
            'payment_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => fake()->randomElement(['paid', 'pending', 'overdue']),
            'payment_method' => fake()->randomElement(['cash', 'online', 'check', 'bank_transfer']),
            'reference_number' => fake()->optional(0.6)->numerify('REF-########'),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
