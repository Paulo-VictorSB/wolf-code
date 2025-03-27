<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('get_task');
    Route::post('/tasks', [TaskController::class, 'store'])->name('new_task');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('update_task');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('delete_task');
});