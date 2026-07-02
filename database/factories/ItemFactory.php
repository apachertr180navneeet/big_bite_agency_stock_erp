<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'variation_id' => \App\Models\Variation::factory(),
            'tax_id' => \App\Models\Tax::factory(),
            'name' => fake()->unique()->word() . ' Item',
            'hsn_hac' => fake()->bothify('HSN-####'),
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
