<?php

use App\Models\Atasan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SkpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('backend/dash.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    // bagian pangkat
    Route::resource('pangkat', PangkatController::class);
    Route::delete('/pangkat/destroy/{uuid}', [PangkatController::class, 'destroy'])->name('pangkat.destroy');

    // bagian atasan 
    Route::resource('atasans', AtasanController::class);
    Route::delete('/atasans/destroy/{uuid}', [AtasanController::class, 'destroy'])->name('atasans.destroy');

    // bagian skp
    Route::resource('skp', SkpController::class);
    Route::delete('/skp/destroy/{uuid}', [SkpController::class, 'destroy'])->name('skp.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';