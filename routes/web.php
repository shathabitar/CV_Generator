<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVController;

Route::get('/', [CVController::class, 'index'])->name('cv.form');

Route::post('/generate', [CVController::class, 'generate'])->name('generate');

Route::post('/store', [CVController::class, 'store'])->name('cv.store');


Route::get('/cv/preview/{id}', [CVController::class, 'preview'])->name('cv.preview');
Route::get('/cv/download-pdf/{id}', [CVController::class, 'downloadCvPdf'])->name('cv.download.pdf');

