<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User_Competence>
 */
class UserCompetenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code_user' => Utilisateur::factory()->create()->code_user,
            'code_comp' => Competence::factory()->create()->code_comp,
        ];
    }
}
