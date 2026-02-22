<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirige a la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {

    $user = auth()->user();

    // Admin → dashboard solo el admi accede 
    if ($user->hasRole('Admin')) {
        return view('dashboard');
    }

    //Usuario normal → perfil
    return redirect()->route('profile.edit');

})->middleware('auth')->name('dashboard');


/*
Rutas protegidas por autenticación
*/
Route::middleware('auth')->group(function () {

    // Perfil usuarios autenticados
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';