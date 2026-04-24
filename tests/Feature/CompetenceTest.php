<?php

namespace Tests\Feature;

use App\Models\Competence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_competences(): void
    {
        Competence::factory()->count(3)->create();

        $response = $this->getJson('/api/competences');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_create_competence(): void
    {
        $response = $this->postJson('/api/competences', [
            'label_comp'       => 'PHP',
            'description_comp' => 'Développement PHP',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['label_comp' => 'PHP']);

        $this->assertDatabaseHas('competences', ['label_comp' => 'PHP']);
    }

    public function test_fails_to_create_competence_without_label(): void
    {
        $response = $this->postJson('/api/competences', [
            'description_comp' => 'Sans label',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['label_comp']);
    }

    public function test_can_show_competence(): void
    {
        $competence = Competence::factory()->create();

        $response = $this->getJson('/api/competences/' . $competence->code_comp);

        $response->assertStatus(200)
                 ->assertJsonFragment(['code_comp' => $competence->code_comp]);
    }

    public function test_show_returns_404_for_nonexistent_competence(): void
    {
        $response = $this->getJson('/api/competences/99999');

        $response->assertStatus(404);
    }

    public function test_can_update_competence(): void
    {
        $competence = Competence::factory()->create();

        $response = $this->putJson('/api/competences/' . $competence->code_comp, [
            'label_comp'       => 'Laravel',
            'description_comp' => 'Framework PHP',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['label_comp' => 'Laravel']);

        $this->assertDatabaseHas('competences', ['label_comp' => 'Laravel']);
    }

    public function test_fails_to_update_competence_without_label(): void
    {
        $competence = Competence::factory()->create();

        $response = $this->putJson('/api/competences/' . $competence->code_comp, [
            'description_comp' => 'Sans label',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['label_comp']);
    }

    public function test_can_delete_competence(): void
    {
        $competence = Competence::factory()->create();

        $response = $this->deleteJson('/api/competences/' . $competence->code_comp);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('competences', ['code_comp' => $competence->code_comp]);
    }

    public function test_can_search_competences(): void
    {
        Competence::factory()->create(['label_comp' => 'Python', 'description_comp' => 'Langage Python']);
        Competence::factory()->create(['label_comp' => 'Java',   'description_comp' => 'Langage Java']);

        $response = $this->getJson('/api/competences/search/Python');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['label_comp' => 'Python']);
    }

    public function test_search_returns_empty_when_no_match(): void
    {
        Competence::factory()->create(['label_comp' => 'PHP']);

        $response = $this->getJson('/api/competences/search/Rust');

        $response->assertStatus(200)
                 ->assertJsonCount(0);
    }
}
