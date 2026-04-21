<?php

use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/departemen', App\Livewire\Master\DepartemenIndex::class)->name('departemen.index');
    Route::get('/jabatan', App\Livewire\Master\JabatanIndex::class)->name('jabatan.index');
    Route::get('/karyawan', App\Livewire\Karyawan\KaryawanIndex::class)->name('karyawan.index');

    Route::post('/logout', function(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
