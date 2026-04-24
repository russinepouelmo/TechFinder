<?php

namespace Tests\Feature;

use App\Models\Competence;
use App\Models\Intervention;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterventionTest extends TestCase
{
    use RefreshDatabase;

    private function createIntervention(): Intervention
    {
        return Intervention::factory()->create();
    }

    private function validPayload(string $clientCode, string $techCode, int $compCode): array
    {
        return [
            'date_int'         => '2026-04-20',
            'note_int'         => 4,
            'commentaire_int'  => 'Intervention test',
            'code_user_client' => $clientCode,
            'code_user_techn'  => $techCode,
            'code_comp'        => $compCode,
        ];
    }

    public function test_can_list_interventions(): void
    {
        $this->createIntervention();
        $this->createIntervention();

        $response = $this->getJson('/api/interventions');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function test_can_create_intervention(): void
    {
        $client    = Utilisateur::factory()->create(['role_user' => 'client']);
        $tech      = Utilisateur::factory()->create(['role_user' => 'technicien']);
        $competence = Competence::factory()->create();

        $response = $this->postJson('/api/interventions', $this->validPayload(
            $client->code_user,
            $tech->code_user,
            $competence->code_comp
        ));

        $response->assertStatus(201)
                 ->assertJsonFragment(['note_int' => 4]);

        $this->assertDatabaseHas('intervention', ['code_user_client' => $client->code_user]);
    }

    public function test_fails_to_create_with_nonexistent_client(): void
    {
        $tech      = Utilisateur::factory()->create(['role_user' => 'technicien']);
        $competence = Competence::factory()->create();

        $response = $this->postJson('/api/interventions', [
            'date_int'         => '2026-04-20',
            'code_user_client' => 'INEXISTANT',
            'code_user_techn'  => $tech->code_user,
            'code_comp'        => $competence->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_user_client']);
    }

    public function test_fails_to_create_with_nonexistent_competence(): void
    {
        $client = Utilisateur::factory()->create(['role_user' => 'client']);
        $tech   = Utilisateur::factory()->create(['role_user' => 'technicien']);

        $response = $this->postJson('/api/interventions', [
            'date_int'         => '2026-04-20',
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $tech->code_user,
            'code_comp'        => 99999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_comp']);
    }

    public function test_fails_to_create_with_invalid_note(): void
    {
        $client    = Utilisateur::factory()->create(['role_user' => 'client']);
        $tech      = Utilisateur::factory()->create(['role_user' => 'technicien']);
        $competence = Competence::factory()->create();

        $payload = $this->validPayload($client->code_user, $tech->code_user, $competence->code_comp);
        $payload['note_int'] = 10;

        $response = $this->postJson('/api/interventions', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['note_int']);
    }

    public function test_fails_to_create_with_missing_required_fields(): void
    {
        $response = $this->postJson('/api/interventions', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['date_int', 'code_user_client', 'code_user_techn', 'code_comp']);
    }

    public function test_can_show_intervention(): void
    {
        $intervention = $this->createIntervention();

        $response = $this->getJson('/api/interventions/' . $intervention->code_int);

        $response->assertStatus(200)
                 ->assertJsonFragment(['code_int' => $intervention->code_int]);
    }

    public function test_show_returns_404_for_nonexistent_intervention(): void
    {
        $response = $this->getJson('/api/interventions/99999');

        $response->assertStatus(404);
    }

    public function test_can_update_intervention(): void
    {
        $intervention = $this->createIntervention();
        $client       = Utilisateur::factory()->create(['role_user' => 'client']);
        $tech         = Utilisateur::factory()->create(['role_user' => 'technicien']);
        $competence   = Competence::factory()->create();

        $response = $this->putJson('/api/interventions/' . $intervention->code_int, [
            'date_int'         => '2026-05-01',
            'note_int'         => 5,
            'commentaire_int'  => 'Mis à jour',
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $tech->code_user,
            'code_comp'        => $competence->code_comp,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['note_int' => 5]);
    }

    public function test_can_delete_intervention(): void
    {
        $intervention = $this->createIntervention();

        $response = $this->deleteJson('/api/interventions/' . $intervention->code_int);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('intervention', ['code_int' => $intervention->code_int]);
    }
}
