<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|max:100',
        ]);
    
        $categorie = new Categorie();
        $categorie->nom = $request->nom;
        $categorie->save();
    
        return back()->with('message', "La catégorie a bien été créé !");
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Categorie $categorie)
{
    $puzzles = $categorie->puzzles()   // relation hasMany
        ->latest()
        ->paginate(12);               // ⬅️ important

    return view('categories.show', compact('categorie', 'puzzles'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
