<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Puzzle;
use App\Models\Categorie;

class PuzzleFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_visiteur_peut_voir_la_liste_des_puzzles()
    {
        $categorie = Categorie::create(['nom' => 'Animaux', 'slug' => 'animaux']);
        $puzzle1 = Puzzle::create([
            'nom' => 'Puzzle Lion',
            'categorie_id' => $categorie->id,
            'description' => 'Un beau lion',
            'prix' => 15.99,
            'note' => 5,
            'image' => 'lion.png'
        ]);

        $response = $this->get('/puzzles');

        $response->assertStatus(200);
        $response->assertSee('Puzzle Lion');
    }

    /** @test */
    public function un_visiteur_peut_voir_le_detail_d_un_puzzle()
    {
        $categorie = Categorie::create(['nom' => 'Animaux', 'slug' => 'animaux']);
        $puzzle = Puzzle::create([
            'nom' => 'Puzzle Tigre',
            'categorie_id' => $categorie->id,
            'description' => 'Description du tigre',
            'prix' => 19.99,
            'note' => 4,
            'image' => 'tigre.png'
        ]);

        $response = $this->get("/puzzles/{$puzzle->id}");

        $response->assertStatus(200);
        $response->assertSee('Puzzle Tigre');
        $response->assertSee('19.99');
    }

    /** @test */
    public function un_admin_peut_creer_un_nouveau_puzzle()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $categorie = Categorie::create(['nom' => 'Nature', 'slug' => 'nature']);

        $response = $this->actingAs($admin)->post('/puzzles', [
            'nom' => 'Puzzle Arbre',
            'categorie_id' => $categorie->id,
            'description' => 'Un grand arbre en bois',
            'note' => 5,
            'prix' => 25.50,
            'image' => 'arbre.png'
        ]);

        $this->assertDatabaseHas('puzzles', [
            'nom' => 'Puzzle Arbre',
            'prix' => 25.50
        ]);

        $puzzleCree = Puzzle::first();
        $response->assertRedirect(route('puzzles.edit', $puzzleCree));
    }

    /** @test */
    public function un_admin_peut_mettre_a_jour_un_puzzle()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $categorie = Categorie::create(['nom' => 'Ville', 'slug' => 'ville']);
        
        $puzzle = Puzzle::create([
            'nom' => 'Ancien Nom',
            'categorie_id' => $categorie->id,
            'description' => 'Ancienne description',
            'prix' => 10.00,
            'note' => 3,
            'image' => 'old.png'
        ]);

        $response = $this->actingAs($admin)->put("/puzzles/{$puzzle->id}", [
            'nom' => 'Nouveau Nom',
            'categorie_id' => $categorie->id,
            'description' => 'Nouvelle description',
            'note' => 4,
            'prix' => 12.00,
            'image' => 'new.png'
        ]);

        $this->assertDatabaseHas('puzzles', [
            'id' => $puzzle->id,
            'nom' => 'Nouveau Nom',
            'prix' => 12.00
        ]);
    }

    /** @test */
    public function un_admin_peut_supprimer_un_puzzle()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $categorie = Categorie::create(['nom' => 'Espace', 'slug' => 'espace']);
        $puzzle = Puzzle::create([
            'nom' => 'Puzzle Fusée',
            'categorie_id' => $categorie->id,
            'description' => 'Zioum',
            'prix' => 30.00,
            'note' => 5,
            'image' => 'fusee.png'
        ]);

        $response = $this->actingAs($admin)->delete("/puzzles/{$puzzle->id}");

        $response->assertRedirect(route('puzzles.index'));
        $response->assertSessionHas('message', 'Le puzzle a bien été supprimé');

        $this->assertDatabaseMissing('puzzles', [
            'id' => $puzzle->id
        ]);
    }
}