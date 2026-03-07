<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = [
            [
                'name' => 'Projeto Horizonte Digital',
                'description' => 'Iniciativa voltada à modernização de processos internos por meio de soluções digitais e automação operacional.',
                'status' => 'active',
                'budget' => '75.00'
            ],
            [
                'name' => 'Projeto Atlas Corporativo',
                'description' => 'Estruturação e padronização de fluxos corporativos para aumento de produtividade e governança.',
                'status' => 'active',
                'budget' => '50.00'
            ],
        ];

        return $this->faker->randomElement($projects);
    }
}
