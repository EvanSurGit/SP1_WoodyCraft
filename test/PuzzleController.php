<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Categorie;
use Illuminate\Support\Str;

class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->query('category'); // ID ou slug
        $query = Puzzle::query()->latest();

        // One-to-many (colonne puzzles.categorie_id)
        if ($category) {
            if (is_numeric($category)) {
                $query->where('categorie_id', $category);
            } else {
                $cat = Categorie::where('slug', $category)->first();
                if ($cat) {
                    $query->where('categorie_id', $cat->id);
                } else {
                    $query->whereRaw('1=0'); // catégorie inconnue
                }
            }
        }

        $puzzles    = $query->paginate(12)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        // Produit vedette = le plus récent
        $offer = \App\Models\Puzzle::latest()->first();

        return view('puzzles.index', compact('puzzles','categories','category','offer'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('puzzles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'description'  => 'required|string',
            'note'         => 'nullable|numeric|min:0|max:5',
            'prix'         => 'required|numeric|min:0',
            'image'        => 'nullable', // fichier OU chaîne
        ]);

        // FICHIER uploadé → garder le NOM ORIGINAL sans date
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // on sécurise juste un peu le nom (retire / et \ pour éviter un path accidentel)
            $original = $file->getClientOriginalName();
            $filename = str_replace(['\\', '/'], '-', trim($original));

            // stocker dans disk "public" → /storage/img/...
            // en BDD on garde "img/filename.ext"
            $path = $file->storeAs('img', $filename, 'public'); // ex: "img/help.png"
            $data['image'] = $path;
        }
        // CHAÎNE simple → préfixe "img/" si besoin
        elseif (!empty($data['image'])) {
            $img = trim($data['image']);
            if (!\Illuminate\Support\Str::startsWith($img, ['http://','https://','img/','/storage/'])) {
                $img = 'img/'.ltrim($img, '/');
            }
            $data['image'] = $img;
        }

        $puzzle = \App\Models\Puzzle::create($data);

        return redirect()->route('puzzles.edit', $puzzle)
            ->with('message', 'Puzzle créé.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        $related = Puzzle::query()
            ->where('id', '!=', $puzzle->id)
            ->when($puzzle->categorie_id, fn($q) =>
                $q->where('categorie_id', $puzzle->categorie_id)
            )
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('puzzles.show', compact('puzzle', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('puzzles.edit', compact('puzzle','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Puzzle $puzzle)
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'description'  => 'required|string',
            'note'         => 'nullable|numeric|min:0|max:5',
            'prix'         => 'required|numeric|min:0',
            'image'        => 'nullable', // fichier OU chaîne
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // garder le NOM ORIGINAL, sans suffixe date
            $original = $file->getClientOriginalName();
            $filename = str_replace(['\\', '/'], '-', trim($original));

            $path = $file->storeAs('img', $filename, 'public'); // "img/help.png"
            $data['image'] = $path;

        } elseif (array_key_exists('image', $data) && !empty($data['image'])) {
            $img = trim($data['image']);
            if (!\Illuminate\Support\Str::startsWith($img, ['http://','https://','img/','/storage/'])) {
                $img = 'img/'.ltrim($img, '/');
            }
            $data['image'] = $img;

        } else {
            // champ omis → ne pas écraser l’ancienne image
            unset($data['image']);
        }

        $puzzle->update($data);

        return redirect()->route('puzzles.edit', $puzzle)
            ->with('message', 'Puzzle mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
        return redirect()->route('puzzles.index')->with('message', "Le puzzle a bien été supprimé");
    }

    public function byCategorie(Categorie $categorie)
    {
        $puzzles = $categorie->puzzles()->get();
        return view('puzzles.byCategorie', compact('categorie', 'puzzles'));
    }
}
