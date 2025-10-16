<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\CategorieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdresseController;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * ADMIN D'ABORD : create/store/edit/update/destroy (protégées)
 */
Route::middleware(['auth','admin'])->group(function () {
    Route::resource('puzzles', PuzzleController::class)
        ->only(['create','store','edit','update','destroy']);

    Route::resource('categories', CategorieController::class)
        ->only(['create','store','edit','update','destroy']);
});

/**
 * PUBLIC ENSUITE : index/show
 */
Route::resource('puzzles', PuzzleController::class)->only(['index','show']);
Route::resource('categories', CategorieController::class)->only(['index','show']);

/** Puzzles par catégorie (public) */
Route::get('categories/{categorie}/puzzles', [PuzzleController::class, 'byCategorie'])
    ->name('puzzles.byCategorie');

/** Panier */
Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
Route::post('/panier/ajouter/{puzzle}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/item/{item}', [CartController::class, 'updateItem'])->name('cart.item.update');
Route::delete('/panier/item/{item}', [CartController::class, 'removeItem'])->name('cart.item.remove');
Route::delete('/panier', [CartController::class, 'clear'])->name('cart.clear');

/** Checkout */
Route::get('/checkout', [CheckoutController::class, 'start'])->name('checkout.start');
Route::get('/checkout/adresse', [CheckoutController::class, 'address'])->name('checkout.address');
Route::post('/checkout/adresse', [CheckoutController::class, 'addressStore'])->name('checkout.address.store');
Route::get('/checkout/recap/{adresse}', [CheckoutController::class, 'review'])->name('checkout.review');
Route::post('/checkout/placer/{adresse}', [CheckoutController::class, 'place'])->name('checkout.place');
Route::get('/checkout/cheque/{commande}', [CheckoutController::class, 'cheque'])->name('checkout.cheque');
Route::get('/checkout/paypal/{commande}', [CheckoutController::class, 'paypal'])->name('checkout.paypal');
Route::get('/checkout/success/{commande}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cheque/{commande}/facture', [CheckoutController::class, 'chequePdf'])->name('checkout.cheque.pdf');

/** Adresses */
Route::resource('adresses', AdresseController::class)
    ->parameters(['adresses' => 'adresse'])
    ->except(['show']);

/** Langue */
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

require __DIR__.'/auth.php';
