<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVController;

Route::get('/', [CVController::class, 'index'])->name('cv.form');
Route::post('/generate', [CVController::class, 'generate'])->name('cv.generate');
Route::post('/store', [CVController::class, 'store'])->name('cv.store');
Route::post('/download-pdf', [CVController::class, 'downloadPdf'])->name('cv.download');

Route::prefix('cv')->name('cv.')->group(function () {
    Route::get('/preview/{id}', [CVController::class, 'preview'])->name('preview');
    Route::get('/download-pdf/{id}', [CVController::class, 'downloadCvPdf'])->name('download.pdf');
    Route::post('/generate-async/{id}', [CVController::class, 'generateCv'])->name('generate.async');
});

