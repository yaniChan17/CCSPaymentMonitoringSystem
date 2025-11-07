<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create blocks first
        $this->call(BlockSeeder::class);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@ccs.edu',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Get blocks
        $blocks = \App\Models\Block::all();

        // Create Treasurer Users (assign to different blocks)
        $treasurer1 = User::factory()->create([
            'name' => 'Maria Santos',
            'email' => 'treasurer@ccs.edu',
            'password' => bcrypt('password'),
            'role' => 'treasurer',
            'block_id' => $blocks[0]->id ?? null,
        ]);

        $treasurer2 = User::factory()->create([
            'name' => 'John Reyes',
            'email' => 'treasurer2@ccs.edu',
            'password' => bcrypt('password'),
            'role' => 'treasurer',
            'block_id' => $blocks[1]->id ?? null,
        ]);

        // Create 10 Students with varied data
        $students = Student::factory(10)->create();

        // Create student user accounts for the first 3 students
        foreach ($students->take(3) as $index => $student) {
            // Assign to blocks in round-robin fashion
            $blockId = $blocks[$index % $blocks->count()]->id ?? null;
            
            User::factory()->create([
                'name' => $student->full_name,
                'email' => $student->email,
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => $student->id,
                'block_id' => $blockId,
            ]);
        }

        // Create payments for each student
        foreach ($students as $student) {
            // Each student gets 2-5 payments
            $paymentCount = rand(2, 5);
            
            for ($i = 0; $i < $paymentCount; $i++) {
                Payment::factory()->create([
                    'student_id' => $student->id,
                    'recorded_by' => rand(0, 1) ? $treasurer1->id : $treasurer2->id,
                ]);
            }

            // Update student balance based on payments
            $totalPaid = $student->payments()->where('status', 'paid')->sum('amount');
            $student->update([
                'balance' => max(0, $student->total_fees - $totalPaid),
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@ccs.edu / password');
        $this->command->info('Treasurer: treasurer@ccs.edu / password');
        $this->command->info('Student (sample): ' . $students->first()->email . ' / password');
    }
}
