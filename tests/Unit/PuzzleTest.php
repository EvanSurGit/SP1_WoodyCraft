<?php

namespace Tests\Unit;

use App\Models\Puzzle;
use App\Models\Categorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PuzzleTest extends TestCase
{
    use RefreshDatabase;

    public function test_puzzle_can_be_created()
    {
        $categorie = Categorie::create(['nom' => 'Test Categorie', 'slug' => 'test-cat']);

        $puzzle = Puzzle::factory()->create([
            'nom' => 'Test Puzzle',
            'categorie_id' => $categorie->id, // Correction ici
            'description' => 'Ceci est un puzzle de test.',
            'prix' => 9.99,
            'note' => 4.5,
            'image' => 'test_image.png',
        ]);

        $this->assertDatabaseHas('puzzles', [
            'nom' => 'Test Puzzle',
        ]);
    }

    public function test_puzzle_creation_fails_with_missing_data()
    {
        $this->expectException(ValidationException::class);

        $puzzleData = [
            'nom' => '',
            'categorie_id' => '',
            'description' => '',
            'prix' => '',
            'note' => '',
            'image' => '',
        ];

        $validator = Validator::make($puzzleData, [
            'nom' => 'required',
            'categorie_id' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric',
            'note' => 'required|numeric',
            'image' => 'required',
        ]);

        $validator->validate();
        Puzzle::create($puzzleData);
    }

    public function test_puzzle_creation_fails_with_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $puzzleData = [
            'nom' => str_repeat('A', 256),
            'categorie_id' => 1,
            'description' => 'Ceci est un puzzle de test.',
            'prix' => -5.99,
            'note' => 4.5,
            'image' => 'test_image.png',
        ];

        $validator = Validator::make($puzzleData, [
            'nom' => 'required|max:255',
            'categorie_id' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric|min:0',
            'note' => 'required|numeric|min:0',
            'image' => 'required',
        ]);

        $validator->validate();
        Puzzle::create($puzzleData);
    }

    public function test_puzzle_creation_fails_with_duplicate_data()
    {
        $categorie = Categorie::create(['nom' => 'Test Categorie', 'slug' => 'test-cat']);

        $puzzleData = [
            'nom' => 'Unique Puzzle',
            'categorie_id' => $categorie->id,
            'description' => 'Ceci est un puzzle de test.',
            'prix' => 9.99,
            'note' => 4.5,
            'image' => 'test_image.png',
        ];

        Puzzle::create($puzzleData);

        $this->expectException(ValidationException::class);

        $validator = Validator::make($puzzleData, [
            'nom' => 'required|unique:puzzles,nom',
            'categorie_id' => 'required',
            'description' => 'required',
            'prix' => 'required|numeric|min:0',
            'note' => 'required|numeric|min:0',
            'image' => 'required',
        ]);

        $validator->validate();
    }

    public function test_puzzle_can_be_read()
    {
        $categorie = Categorie::create(['nom' => 'Test Categorie', 'slug' => 'test-cat']);

        $puzzle = Puzzle::factory()->create([
            'nom'        => 'Test Puzzle',
            'categorie_id' => $categorie->id,
            'description'=> 'Ceci est un puzzle de test.',
            'prix'       => 9.99,
            'note'       => 4.5,
            'image'      => 'test.png' // Obligatoire pour la BDD
        ]);

        $foundPuzzle = Puzzle::find($puzzle->id);

        $this->assertNotNull($foundPuzzle);
        $this->assertEquals($puzzle->nom, $foundPuzzle->nom);
    }

    public function test_puzzle_can_be_updated()
    {
        $categorie = Categorie::create(['nom' => 'Test Categorie', 'slug' => 'test-cat']);
        
        $puzzle = Puzzle::factory()->create([
            'categorie_id' => $categorie->id,
            'note' => 5,
            'image' => 'test.png'
        ]);

        $puzzle->nom = 'Nom mis à jour';
        $puzzle->save();

        $this->assertEquals('Nom mis à jour', $puzzle->fresh()->nom);
    }

    public function test_puzzle_can_be_deleted()
    {
        $categorie = Categorie::create(['nom' => 'Test Categorie', 'slug' => 'test-cat']);
        
        $puzzle = Puzzle::factory()->create([
            'categorie_id' => $categorie->id,
            'note' => 5,
            'image' => 'test.png'
        ]);

        $puzzle->delete();

        $this->assertModelMissing($puzzle);
    }
}