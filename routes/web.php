<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SkpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\PangkatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\SkpAtasanController;
use App\Http\Controllers\RencanaKerjaController;
use App\Http\Controllers\PerilakuKerjaController;
use App\Http\Controllers\EvaluasiAtasanController;
use App\Http\Controllers\KegiatanHarianController;
use App\Http\Controllers\ValidasiHarianController;
use App\Http\Controllers\IndikatorKinerjaController;
use App\Http\Controllers\RencanaKerjaPegawaiController;
use Spatie\Permission\Contracts\Role;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => ['auth']], function () {

    // bagian dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');
    Route::post('/getEvaluasiPegawai', [HomeController::class, 'getEvaluasiPegawai'])->name('getEvaluasiPegawai');

    // bagian role
    Route::resource('roles', RoleController::class);
    Route::delete('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // bagian user
    Route::resource('users', UserController::class);
    Route::delete('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // bagian pangkat
    Route::resource('pangkat', PangkatController::class);
    Route::delete('/pangkat/destroy/{uuid}', [PangkatController::class, 'destroy'])->name('pangkat.destroy');

    // bagian atasan 
    Route::resource('atasans', AtasanController::class);
    Route::delete('/atasans/destroy/{uuid}', [AtasanController::class, 'destroy'])->name('atasans.destroy');

    // bagian skp
    Route::resource('skp', SkpController::class);
    Route::delete('/skp/destroy/{uuid}', [SkpController::class, 'destroy'])->name('skp.destroy');
    Route::put('/skp/{id}/toggle', [SkpController::class, 'toggle'])->name('skp.toggle');


    // bagian skp atasan
    Route::resource('skp_atasan', SkpAtasanController::class);
    Route::delete('/skp_atasan/destroy/{uuid}', [SkpAtasanController::class, 'destroy'])->name('skp_atasan.destroy');

    // bagian Rencana Kerja Atasan
    Route::put('/rencana-kerja/{uuid}/update', [RencanaKerjaController::class, 'update'])->name('rencana-kerja.update');
    Route::resource('rencana-kerja', RencanaKerjaController::class);
    Route::delete('/rencana-kerja/destroy/{uuid}', [RencanaKerjaController::class, 'destroy'])->name('rencana-kerja.destroy');

    // bagian Rencana Kerja Pegawai
    Route::resource('rencana-pegawai', RencanaKerjaPegawaiController::class);


    // bagian indikator
    Route::put('/indikator-kinerja/{uuid}/update', [IndikatorKinerjaController::class, 'update'])->name('indikator-kinerja.update');
    Route::resource('indikator-kinerja', IndikatorKinerjaController::class);
    Route::delete('/indikator-kinerja/destroy/{uuid}', [IndikatorKinerjaController::class, 'destroy'])->name('indikator-kinerja.destroy');


    // route perilaku
    Route::resource('perilaku', PerilakuKerjaController::class);
    Route::put('/perilaku/update/{uuid}', [PerilakuKerjaController::class, 'update'])->name('perilaku.update');
    Route::delete('/perilaku/destroy/{uuid}', [PerilakuKerjaController::class, 'destroy'])->name('perilaku.destroy');

    // route category
    Route::resource('category', CategoriController::class);
    Route::delete('/category/destroy/{uuid}', [CategoriController::class, 'destroy'])->name('category.destroy');


    // bagian profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // bagian setting
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingController::class, 'destroy'])->name('settings.destroy');

    // bagian validasi 
    Route::resource('validasi', ValidasiController::class);

    Route::resource('validasi-harian', ValidasiHarianController::class);
    Route::get('/validasi-harian/user/{userId}', [ValidasiHarianController::class, 'getByUser']);
    // Rute untuk update
    Route::put('/validasi-harian/{user_id}', [ValidasiHarianController::class, 'update'])->name('validasi-harian.update');

    // bagian harian
    Route::resource('harian-pegawai', KegiatanHarianController::class);
    Route::delete('/harian-pegawai/destroy/{uuid}', [KegiatanHarianController::class, 'destroy'])->name('harian-pegawai.destroy');
    Route::patch('/harian-pegawai/{uuid}/update', [KegiatanHarianController::class, 'update'])->name('harian-pegawai.update');

    // upload file realisasi
    Route::post('/realisasi/store', [RealisasiController::class, 'store'])->name('realisasi.store');

    // bagian evaluasi pegawai
    Route::resource('evaluasi-pegawai', EvaluasiController::class);
    Route::delete('/evaluasi-pegawai/destroy/{uuid}', [EvaluasiController::class, 'destroy'])->name('evaluasi-pegawai.destroy');
    Route::get('evaluasi-pegawai/{uuid}/pdf', [EvaluasiController::class, 'generatePdf'])->name('evaluasi-pegawai.pdf');

    // bagian evaluasi atasan
    Route::resource('evaluasi-atasan', EvaluasiAtasanController::class)
        ->parameters(['evaluasi-atasan' => 'uuid']);


    Route::get('/evaluasi-atasan/user/{userId}', [EvaluasiAtasanController::class, 'getByUser']);
});

require __DIR__ . '/auth.php';