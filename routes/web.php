<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ---------- Master Items ----------
Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index']);
Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
Route::get('/master-items/export', [App\Http\Controllers\MasterItemsController::class, 'export']);
Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);

Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);

Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);

// ---------- Kategori ----------
// ---------- Kategori ----------
Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index']);
Route::get('/kategori/search', [App\Http\Controllers\KategoriController::class, 'search']);
Route::get('/kategori/form/{method}/{id?}', [App\Http\Controllers\KategoriController::class, 'formView']);
Route::post('/kategori/form/{method}/{id?}', [App\Http\Controllers\KategoriController::class, 'formSubmit']);
Route::get('/kategori/view/{kode}', [App\Http\Controllers\KategoriController::class, 'singleView']);
Route::get('/kategori/pdf/{kode}', [App\Http\Controllers\KategoriController::class, 'pdf']);
Route::get('/kategori/delete/{id}', [App\Http\Controllers\KategoriController::class, 'delete']);