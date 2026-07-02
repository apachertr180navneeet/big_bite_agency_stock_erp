<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate core dummy data using factories
        \App\Models\Company::factory(5)->create();
        \App\Models\SubCompany::factory(5)->create();
        \App\Models\Tax::factory(10)->create();
        \App\Models\Variation::factory(10)->create();
        \App\Models\Item::factory(20)->create();
        \App\Models\Contact::factory(5)->create();
    }
}
