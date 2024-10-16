<?php

use App\Http\Controllers\ExportController;
use App\Livewire\ManageTimeLogs;
use App\Livewire\TimeLogs;
use App\Livewire\TimeLogForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['auth', 'role:manager'])->group(function () {
        Route::get('/edit-log-hours/{timeLogId}',TimeLogForm::class)->name('edit.log.hours');
        Route::get('/manage-time-logs', ManageTimeLogs::class)->name('manage.time.logs');
        Route::get('/export-time-logs', [ExportController::class,'exportToCsv'])->name('export.time.logs');
    
    });
    Route::middleware(['auth'])->group(function () {
        Route::get('/log-hours', TimeLogForm::class)->name('log.hours');
        Route::get('/my-logs', TimeLogs::class)->name('my.logs');    
    });

});

