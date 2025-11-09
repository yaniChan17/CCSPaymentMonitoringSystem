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
            ['name' => 'Block A', 'program' => 'BSCS', 'year_level' => 3],
            ['name' => 'Block B', 'program' => 'BSCS', 'year_level' => 3],
            ['name' => 'Block C', 'program' => 'BSIT', 'year_level' => 2],
            ['name' => 'Block D', 'program' => 'BSIT', 'year_level' => 2],
        ];

        foreach ($blocks as $block) {
            \App\Models\Block::create($block);
        }
    }
}
