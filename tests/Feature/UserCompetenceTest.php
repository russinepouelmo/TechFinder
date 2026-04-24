<?php

namespace Tests\Feature;

use App\Models\Competence;
use App\Models\User_Competence;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCompetenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_user_competences(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $competence  = Competence::factory()->create();

        User_Competence::create([
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response = $this->getJson('/api/user-competences');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_can_assign_competence_to_user(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $competence  = Competence::factory()->create();

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_competence', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);
    }

    public function test_fails_to_assign_duplicate_competence(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $competence  = Competence::factory()->create();

        User_Competence::create([
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response->assertStatus(409);
    }

    public function test_fails_to_assign_with_nonexistent_user(): void
    {
        $competence = Competence::factory()->create();

        $response = $this->postJson('/api/user-competences', [
            'code_user' => 'INEXISTANT',
            'code_comp' => $competence->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_user']);
    }

    public function test_fails_to_assign_with_nonexistent_competence(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => 99999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_comp']);
    }

    public function test_can_get_competences_by_user(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $comp1       = Competence::factory()->create();
        $comp2       = Competence::factory()->create();

        User_Competence::create(['code_user' => $utilisateur->code_user, 'code_comp' => $comp1->code_comp]);
        User_Competence::create(['code_user' => $utilisateur->code_user, 'code_comp' => $comp2->code_comp]);

        $response = $this->getJson('/api/user-competences/user/' . $utilisateur->code_user);

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function test_get_competences_by_user_returns_404_when_none(): void
    {
        $response = $this->getJson('/api/user-competences/user/INEXISTANT');

        $response->assertStatus(404);
    }

    public function test_can_remove_competence_from_user(): void
    {
        $utilisateur = Utilisateur::factory()->create();
        $competence  = Competence::factory()->create();

        User_Competence::create([
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response = $this->deleteJson('/api/user-competences/1', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_competence', [
            'code_user' => $utilisateur->code_user,
            'code_comp' => $competence->code_comp,
        ]);
    }

    public function test_remove_returns_404_when_not_assigned(): void
    {
        $response = $this->deleteJson('/api/user-competences/1', [
            'code_user' => 'USR999',
            'code_comp' => 99999,
        ]);

        $response->assertStatus(404);
    }
}
