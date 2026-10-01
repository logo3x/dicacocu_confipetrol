<?php

namespace Database\Factories;

use App\Enums\IconoCarpeta;
use App\Models\Carpeta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Carpeta>
 */
class CarpetaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->words(2, true),
            'codigo' => strtoupper(fake()->unique()->bothify('???-####')),
            'descripcion' => fake()->sentence(),
            'parent_id' => null,
            'created_by' => User::factory(),
            'color' => fake()->hexColor(),
            'icono' => fake()->randomElement(IconoCarpeta::cases())->value,
            'is_public' => false,
            'orden' => fake()->numberBetween(0, 10),
        ];
    }

    /** Subcarpeta colgada de otra carpeta. */
    public function dentroDe(Carpeta $padre): static
    {
        return $this->state(fn (): array => ['parent_id' => $padre->getKey()]);
    }

    /** Carpeta visible desde la página pública. */
    public function publica(): static
    {
        return $this->state(fn (): array => ['is_public' => true]);
    }
}
