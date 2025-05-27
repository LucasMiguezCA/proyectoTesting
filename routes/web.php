<?php

use App\Http\Controllers\TareasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tareas', [TareasController::class, 'index'])->name('tareas.index');

Route::get('/test', fn() => 'Funciona!');