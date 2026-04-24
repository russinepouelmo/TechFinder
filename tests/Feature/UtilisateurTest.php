<?php

namespace Tests\Feature;

use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UtilisateurTest extends TestCase
{
    use RefreshDatabase;

    private array $validPayload = [
        'nom_user'      => 'Dupont',
        'prenom_user'   => 'Marie',
        'login_user'    => 'marie.dupont',
        'password_user' => 'secret1234',
        'tel_user'      => '0612345678',
        'sexe_user'     => 'F',
        'role_user'     => 'technicien',
        'etat_user'     => 'actif',
    ];

    public function test_can_list_utilisateurs(): void
    {
        Utilisateur::factory()->count(3)->create();

        $response = $this->getJson('/api/utilisateurs');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_create_utilisateur(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->validPayload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['code_user', 'nom_user', 'login_user']);

        $this->assertDatabaseHas('utilisateurs', ['login_user' => 'marie.dupont']);
        $savedUser = Utilisateur::where('login_user', 'marie.dupont')->firstOrFail();
        $this->assertTrue(Hash::check('secret1234', $savedUser->password_user));
    }

    public function test_generates_distinct_matricules_for_new_users(): void
    {
        $first = $this->postJson('/api/utilisateurs', $this->validPayload)->assertStatus(201);
        $secondPayload = array_merge($this->validPayload, ['login_user' => 'marie.dupont.2']);
        $second = $this->postJson('/api/utilisateurs', $secondPayload)->assertStatus(201);

        $this->assertNotEquals(
            $first->json('code_user'),
            $second->json('code_user')
        );
    }

    public function test_fails_to_create_with_duplicate_login(): void
    {
        Utilisateur::factory()->create(['code_user' => 'USR999', 'login_user' => 'marie.dupont']);

        $response = $this->postJson('/api/utilisateurs', $this->validPayload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login_user']);
    }

    public function test_fails_to_create_with_invalid_role(): void
    {
        $payload = array_merge($this->validPayload, ['role_user' => 'superadmin']);

        $response = $this->postJson('/api/utilisateurs', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['role_user']);
    }

    public function test_fails_to_create_with_invalid_etat(): void
    {
        $payload = array_merge($this->validPayload, ['etat_user' => 'Bloquer']);

        $response = $this->postJson('/api/utilisateurs', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['etat_user']);
    }

    public function test_fails_to_create_with_invalid_sexe(): void
    {
        $payload = array_merge($this->validPayload, ['sexe_user' => 'X']);

        $response = $this->postJson('/api/utilisateurs', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['sexe_user']);
    }

    public function test_fails_to_create_with_missing_required_fields(): void
    {
        $response = $this->postJson('/api/utilisateurs', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nom_user', 'login_user', 'password_user']);
    }

    public function test_can_show_utilisateur(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $response = $this->getJson('/api/utilisateurs/' . $utilisateur->code_user);

        $response->assertStatus(200)
                 ->assertJsonFragment(['code_user' => $utilisateur->code_user]);
    }

    public function test_show_returns_404_for_nonexistent_utilisateur(): void
    {
        $response = $this->getJson('/api/utilisateurs/INEXISTANT');

        $response->assertStatus(404);
    }

    public function test_can_update_utilisateur(): void
    {
        $utilisateur = Utilisateur::factory()->create(['code_user' => 'USR001']);

        $updatePayload = [
            'nom_user'      => 'Martin',
            'prenom_user'   => 'Jean',
            'login_user'    => 'jean.martin',
            'password_user' => 'newpassword',
            'tel_user'      => '0698765432',
            'sexe_user'     => 'M',
            'role_user'     => 'client',
            'etat_user'     => 'inactif',
        ];

        $response = $this->putJson('/api/utilisateurs/' . $utilisateur->code_user, $updatePayload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nom_user' => 'Martin']);

        $this->assertDatabaseHas('utilisateurs', ['nom_user' => 'Martin', 'code_user' => 'USR001']);
    }

    public function test_fails_to_update_with_invalid_data(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $response = $this->putJson('/api/utilisateurs/' . $utilisateur->code_user, [
            'role_user' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_can_delete_utilisateur(): void
    {
        $utilisateur = Utilisateur::factory()->create();

        $response = $this->deleteJson('/api/utilisateurs/' . $utilisateur->code_user);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('utilisateurs', ['code_user' => $utilisateur->code_user]);
    }
}
