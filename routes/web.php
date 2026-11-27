<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CustomListController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Captura cualquier ruta que no coincida con las anteriores y redirige al inicio
Route::fallback(function () {
    return redirect('/');
});

Route::get('/', function () {
    return view('inicio');
});

// ruta de Character
Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
// ruta de Character show
Route::get('/character/{id}', [CharacterController::class, 'show'])->name('characters.show');
// ruta de Episode
Route::get('/episodes', [EpisodeController::class, 'index'])->name('episodes.index');
// ruta de Episode show
Route::get('/episode/{id}', [EpisodeController::class, 'show'])->name('episodes.show');

// ruta de locaciones
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');

Route::get('/locations/{id}', [LocationController::class, 'show'])->name('locations.show');

// favoritos rutas
Route::middleware(['auth'])->prefix('favoritos')->name('favorites.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/{type}/{id}', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/{favorite}', [FavoriteController::class, 'destroy'])->name('destroy');
});

// login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Ruta para cerrar sesión (requiere estar autenticado)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta reseteo password
// Formulario para ingresar el correo
Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
// Envía el enlace de recuperación al correo
Route::post('forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
// Formulario para ingresar la nueva contraseña (recibe el token por URL)
Route::get('reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');

// Actualiza la contraseña en la base de datos
Route::post('reset-password', [PasswordResetController::class, 'update'])->name('password.update');

// profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Panel de administración de usuarios (Protegido: Auth + AdminMiddleware)
// Solo registramos las acciones que realmente usamos: listar, editar y eliminar
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
});
// Listas personalizadas (Requiere usuario autenticado)
Route::middleware(['auth'])->prefix('listas')->name('listas.')->group(function () {
    Route::get('/', [CustomListController::class, 'index'])->name('index');
    Route::post('/', [CustomListController::class, 'store'])->name('store');
    Route::get('/{customList}', [CustomListController::class, 'show'])->name('show');
    Route::put('/{customList}', [CustomListController::class, 'update'])->name('update');
    Route::delete('/{customList}', [CustomListController::class, 'destroy'])->name('destroy');
    Route::post('/{customList}/items', [CustomListController::class, 'addItem'])->name('items.add');
    Route::delete('/{customList}/items/{itemId}', [CustomListController::class, 'removeItem'])->name('items.remove');
});
