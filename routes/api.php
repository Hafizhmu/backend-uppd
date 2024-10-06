<?php

use App\Http\Controllers\BeritaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//GET
Route::get('index', action: [BeritaController::class, 'index']);
Route::get('getImage/{id}', action: [BeritaController::class, 'getImage']);
Route::get('berita/{id}', action: [BeritaController::class, 'show']);











//POST
Route::post('add/berita', action: [BeritaController::class, 'store']);
