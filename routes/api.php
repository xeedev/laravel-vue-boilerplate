<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
Route::get('/files', [FileController::class, 'index']);
Route::post('/files/upload', [FileController::class, 'upload']);
Route::get('/files/{id}/download', [FileController::class, 'download'])
    ->name('documents.download');
Route::get('/files/{id}/view', [FileController::class, 'view'])
    ->name('documents.view'); // <- add this
    Route::get('/files/{id}/view', [FileController::class, 'view'])->name('documents.view');
Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('documents.delete');
