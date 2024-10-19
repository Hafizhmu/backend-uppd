<?php

use App\Http\Controllers\BeritaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//GET
Route::get('index', action: [BeritaController::class, 'index']);
Route::get('beritaAwal', action: [BeritaController::class, 'beritaAwal']);
Route::get('getImage', action: [BeritaController::class, 'getImage']);
Route::get('berita/{id}', action: [BeritaController::class, 'show']);






//PUT
Route::post('update/berita/{id}', action: [BeritaController::class, 'update']);



//POST
Route::post('add/berita', action: [BeritaController::class, 'store']);


Route::destroy('add/berita', action: [BeritaController::class, 'store']);