<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tasks = [
            [
                'description' => 'Definir requisitos do sistema',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDay(),
                'status' => 'not_completed',
            ],
            [
                'description' => 'Configurar ambiente de desenvolvimento',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDay(),
                'status' => 'not_completed',
            ],
            [
                'description' => 'Implementar autenticação de usuários',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDay(),
                'status' => 'not_completed',
            ],
        ];

        return $this->faker->randomElement($tasks);
    }
}
