<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

// Route::middleware(['web','auth'])->prefix('/role')->name('role.')->group(function(){
//     Route::get('/', [RoleController::class,'index'])->name('index');
// });

Route::middleware(['web','auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('/system')->name('roles.')->group(function() {
        Route::get('/roles', [RoleController::class, 'index'])->name('index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('create');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('edit');
    });
});