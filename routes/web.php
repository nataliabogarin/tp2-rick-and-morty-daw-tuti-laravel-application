
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CustomListController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Explorador principal
Route::get('/', [ExplorerController::class, 'index'])->name('home');

Route::get('/explorador/personajes/{id}', [ExplorerController::class, 'character'])
    ->name('explorer.character');

// Sincronización con la API
Route::post('/sincronizar', [ExplorerController::class, 'sync'])
    ->middleware(['auth', AdminMiddleware::class])
    ->name('explorer.sync');

// Explorador público de personajes
Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/characters/{id}', [CharacterController::class, 'show'])->name('characters.show');

// Explorador público de episodios
Route::get('/episodes', [EpisodeController::class, 'index'])->name('episodes.index');
Route::get('/episodes/{id}', [EpisodeController::class, 'show'])->name('episodes.show');

// Explorador público de locaciones
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{id}', [LocationController::class, 'show'])->name('locations.show');

// Favoritos
Route::middleware('auth')->prefix('favoritos')->name('favorites.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/{type}/{id}', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/{favorite}', [FavoriteController::class, 'destroy'])->name('destroy');
});

// Autenticación y recuperación de contraseña
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

// Cierre de sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Administración de usuarios
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('users', UserController::class)
        ->only(['index', 'edit', 'update', 'destroy']);
});

// Listas personalizadas
Route::middleware('auth')->prefix('listas')->name('listas.')->group(function () {
    Route::get('/', [CustomListController::class, 'index'])->name('index');
    Route::post('/', [CustomListController::class, 'store'])->name('store');
    Route::get('/{customList}', [CustomListController::class, 'show'])->name('show');
    Route::put('/{customList}', [CustomListController::class, 'update'])->name('update');
    Route::delete('/{customList}', [CustomListController::class, 'destroy'])->name('destroy');
    Route::post('/{customList}/items', [CustomListController::class, 'addItem'])->name('items.add');
    Route::delete('/{customList}/items/{itemId}', [CustomListController::class, 'removeItem'])->name('items.remove');
});
