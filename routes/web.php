<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return 'ini adalah dashboard,nanti akan kita buatkan dashboard yang lebih menarik';
    })->name('dashboard');
});
