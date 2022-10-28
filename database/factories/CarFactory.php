<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // Generate 10 random cars
            'name' => fake()->randomElement(['BMW', 'Mercedes', 'Audi', 'Volkswagen', 'Opel', 'Ford', 'Renault', 'Peugeot', 'Citroen', 'Fiat']),
            // Generate 10 random colors
            'color' => fake()->randomElement(['red', 'blue', 'green', 'yellow', 'black', 'white', 'grey', 'brown', 'orange', 'purple']),
            // Generate 10 random types
            'type' => fake()->randomElement(['sedan', 'hatchback', 'coupe', 'convertible', 'suv', 'crossover', 'wagon', 'minivan', 'pickup', 'van']),
        ];
    }
}
