<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blocks = [
            ['name' => 'Block A', 'description' => 'First year students - Section A'],
            ['name' => 'Block B', 'description' => 'First year students - Section B'],
            ['name' => 'Block C', 'description' => 'Second year students - Section A'],
            ['name' => 'Block D', 'description' => 'Second year students - Section B'],
            ['name' => 'Block E', 'description' => 'Third year students - Section A'],
            ['name' => 'Block F', 'description' => 'Third year students - Section B'],
        ];

        foreach ($blocks as $block) {
            \App\Models\Block::create($block);
        }
    }
}
