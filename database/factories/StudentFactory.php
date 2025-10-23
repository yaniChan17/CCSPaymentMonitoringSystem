<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = ['BSCS', 'BSIT', 'BSIS', 'ACT'];
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $blocks = ['A', 'B', 'C', '1', '2', '3'];
        $totalFees = fake()->randomElement([25000, 30000, 35000, 40000]);

        return [
            'student_id' => fake()->unique()->numerify('2024-#####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'contact_number' => fake()->phoneNumber(),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->randomElement($yearLevels),
            'block' => fake()->randomElement($blocks),
            'total_fees' => $totalFees,
            'balance' => fake()->randomFloat(2, 0, $totalFees),
            'status' => fake()->randomElement(['active', 'inactive', 'graduated']),
        ];
    }
}
