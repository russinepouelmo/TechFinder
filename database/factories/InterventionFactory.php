<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Intervention>
 */
class InterventionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date_int'         => $this->faker->date(),
            'note_int'         => $this->faker->numberBetween(0, 5),
            'commentaire_int'  => $this->faker->sentence(),
            'code_user_client' => Utilisateur::factory()->create(['role_user' => 'client'])->code_user,
            'code_user_techn'  => Utilisateur::factory()->create(['role_user' => 'technicien'])->code_user,
            'code_comp'        => Competence::factory()->create()->code_comp,
        ];
    }
}
