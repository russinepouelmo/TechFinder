<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\User_Competence;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class UserCompetenceSeeder extends Seeder
{
    public function run(): void
    {
        $techniciens = Utilisateur::where('role_user', 'technicien')->get();
        $competences = Competence::all();

        if ($techniciens->isEmpty() || $competences->isEmpty()) {
            return;
        }

        $count = min(30, $techniciens->count());

        foreach ($techniciens->random($count) as $technicien) {
            $sample = $competences->random(rand(1, min(5, $competences->count())));
            foreach ($sample as $competence) {
                User_Competence::firstOrCreate([
                    'code_user' => $technicien->code_user,
                    'code_comp' => $competence->code_comp,
                ]);
            }
        }
    }
}
