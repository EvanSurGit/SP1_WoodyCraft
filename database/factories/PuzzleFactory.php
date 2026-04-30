<?php

namespace Database\Factories;

use App\Models\Puzzle;
use App\Models\Categorie; // N'oublie pas d'importer le modèle Categorie
use Illuminate\Database\Eloquent\Factories\Factory;

class PuzzleFactory extends Factory
{
    protected $model = Puzzle::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->words(3, true), // Un nom de 3 mots
            
            // On remplace 'categorie' par 'categorie_id'
            // On génère une catégorie à la volée s'il n'y en a pas, pour éviter les erreurs de clé étrangère
            'categorie_id' => function () {
                return Categorie::first()->id ?? Categorie::create(['nom' => 'Défaut'])->id;
            },
            
            'description' => $this->faker->sentence,
            'prix' => $this->faker->randomFloat(2, 1, 100),
            'note' =>  $this->faker->randomFloat(1, 0, 5),
            'image' => 'img/default_puzzle.png', // Un nom de fichier simple pour les tests
        ];
    }
}