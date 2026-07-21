<?php

namespace Database\Factories;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Documento>
 */
class DocumentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['procedimiento', 'instructivo', 'formato', 'manual', 'politica', 'norma', 'reglamento'];
        $estados = ['borrador', 'en_revision', 'aprobado', 'divulgado', 'verificado', 'rechazado'];

        return [
            'titulo' => fake()->sentence(5),
            'codigo' => strtoupper(fake()->lexify('???-####')),
            'descripcion' => fake()->paragraph(),
            'tipo_documento' => fake()->randomElement($tipos),
            'estado' => fake()->randomElement($estados),
            'created_by' => User::factory(),
            'fecha_emision' => fake()->dateTimeBetween('-2 years', 'now'),
            'fecha_revision' => fake()->dateTimeBetween('now', '+1 year'),
            'version_actual' => 1,
            'requiere_firma' => fake()->boolean(20),
            'confidencial' => fake()->boolean(10),
            'tags' => fake()->randomElements(['urgente', 'sgc', 'sst', 'ecopetrol', 'revision'], 2),
        ];
    }

    public function borrador(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'borrador']);
    }

    public function aprobado(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'aprobado']);
    }

    public function divulgado(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'divulgado']);
    }
}
