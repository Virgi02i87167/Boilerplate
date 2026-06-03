<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\PrecioTemporadaController;
use App\Models\Habitacion;
use App\Models\Cliente;
use Illuminate\Support\Facades\Route;

// Redirige a la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', function () {
    // Sincronización masiva de estados (Alta Eficiencia)
    Habitacion::syncAllStatuses();

    $totalHabitaciones = Habitacion::count();
    $habitacionesDisponibles = Habitacion::where('estado', 'disponible')->count();
    $habitacionesOcupadas = Habitacion::where('estado', 'ocupada')->count();
    $totalClientes = Cliente::count();
    
    $tasaOcupacion = $totalHabitaciones > 0 ? ($habitacionesOcupadas / $totalHabitaciones) * 100 : 0;

    return view('dashboard', compact('totalHabitaciones', 'habitacionesDisponibles', 'habitacionesOcupadas', 'totalClientes', 'tasaOcupacion'));
})->middleware(['auth', 'can:acceder-dashboard'])->name('dashboard');

// Perfil
Route::middleware(['auth', 'can:editar-perfil'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

/*
Rutas protegidas por autenticación
*/
Route::middleware(['auth'])->group(function () {

    // Gestión de usuarios
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', RoleController::class);
    // Gestión de clientes
    Route::resource('clientes', \App\Http\Controllers\ClienteController::class);

    // Gestión de habitaciones
    Route::delete('habitaciones/{habitacion}/imagen-principal', [HabitacionController::class, 'destroyMainImagen'])->name('habitaciones.destroyMainImagen');
    Route::delete('habitaciones-imagenes/{imagen}', [HabitacionController::class, 'destroyAdditionalImagen'])->name('habitaciones.destroyAdditionalImagen');
    Route::resource('habitaciones', HabitacionController::class)->parameters(['habitaciones' => 'habitacion']);
    Route::resource('precios-temporada', PrecioTemporadaController::class)->parameters([
        'precios-temporada' => 'precioTemporada'
    ]);
    
    Route::get('reservaciones/listado', [ReservacionController::class, 'reservations'])->name('reservaciones.listado');
    Route::get('reservaciones/cotizar', [ReservacionController::class, 'cotizar'])->name('reservaciones.cotizar');
    Route::post('reservaciones/{reservacion}/procesar-pago', [ReservacionController::class, 'procesarPago'])->name('reservaciones.procesarPago');
    Route::resource('reservaciones', ReservacionController::class)->parameters([
        'reservaciones' => 'reservacion'
    ]);
});


require __DIR__ . '/auth.php';