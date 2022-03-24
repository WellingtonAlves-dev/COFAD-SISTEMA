<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessoresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "ativo" => $this->faker->boolean(),
            "matricula" => $this->faker->numberBetween(9999),
            "nome" => $this->faker->name()
        ];
    }
}
