<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalCalvingsController;
use Illuminate\Support\Facades\Route;

use App\Models\AnimalDetail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



//this below group is used to manage about animal_details
Route::middleware('auth')->prefix('animal')->group(function () {
    Route::get('/', [AnimalController::class, 'index'])->name('animal.list');
    Route::get('/create', [AnimalController::class, 'create'])->name('animal.create');
    Route::post('/store', [AnimalController::class, 'store'])->name('animal.store');
    
    Route::group(['prefix'=>'{animaldetail}'],function(){
       
        Route::get('/edit', [AnimalController::class, 'edit'])->name('animal.edit');
        Route::post('/update', [AnimalController::class, 'update'])->name('animal.update');

        Route::get('/delete', [AnimalController::class, 'delete'])->name('animal.delete');
        Route::post('/destroy', [AnimalController::class, 'destroy'])->name('animal.destroy');
    });
 
});


//this below group is used to manage animal_calving details
Route::middleware('auth')->prefix('animal_calvings')->group(function () {

    Route::get('/', [AnimalCalvingsController::class, 'index'])->name('animal_calving.list');
  
    Route::get('/create', [AnimalCalvingsController::class, 'create'])->name('animal_calving.create');
    Route::post('/store', [AnimalCalvingsController::class, 'store'])->name('animal_calvings.store');
 
});















require __DIR__.'/auth.php';
