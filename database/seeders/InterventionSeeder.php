<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Intervention;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class InterventionSeeder extends Seeder
{
    public function run(): void
    {
        $clients     = Utilisateur::where('role_user', 'client')->get();
        $techniciens = Utilisateur::where('role_user', 'technicien')->get();
        $competences = Competence::all();

        for ($i = 0; $i < 50; $i++) {
            Intervention::create([
                'date_int'        => fake()->date(),
                'note_int'        => fake()->numberBetween(0, 5),
                'commentaire_int' => fake()->sentence(),
                'code_user_client' => $clients->random()->code_user,
                'code_user_techn'  => $techniciens->random()->code_user,
                'code_comp'        => $competences->random()->code_comp,
            ]);
        }
    }
}
