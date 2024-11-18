<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedingEventsController;
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

        Route::get('/view', [AnimalController::class, 'view'])->name('animal.view');

        Route::get('/delete', [AnimalController::class, 'delete'])->name('animal.delete');
        Route::post('/destroy', [AnimalController::class, 'destroy'])->name('animal.destroy');
    });
 
});


//this below group is used to manage animal_calving details
Route::middleware('auth')->prefix('animal_calvings')->group(function () {

    Route::get('/', [AnimalCalvingsController::class, 'index'])->name('animal_calvings.list');
  
    Route::get('/create', [AnimalCalvingsController::class, 'create'])->name('animal_calving.create');
    Route::post('/store', [AnimalCalvingsController::class, 'store'])->name('animal_calvings.store');

    Route::group(['prefix'=>'{animalcalvings}'],function(){
       
        Route::get('/edit', [AnimalCalvingsController::class, 'edit'])->name('animal_calvings.edit');
        Route::post('/update', [AnimalCalvingsController::class, 'update'])->name('animal_calvings.update');

        Route::get('/delete', [AnimalCalvingsController::class, 'delete'])->name('animal_calvings.delete');
        Route::post('/destroy', [AnimalCalvingsController::class, 'destroy'])->name('animal_calvings.destroy');

      
    });

  
    Route::group(['prefix'=>'{calfId}'],function(){
       
        Route::get('/details', [AnimalCalvingsController::class, 'getCalfDetails']);

      
    });

   
 
});

//this below group is used to animal_breedings details
Route::middleware('auth')->prefix('animal_breedings')->group(function () {

    Route::get('/', [BreedingEventsController::class, 'index'])->name('animal_breedings.list');
  
    Route::get('/create', [BreedingEventsController::class, 'create'])->name('animal_breedings.create');
    Route::post('/store', [BreedingEventsController::class, 'store'])->name('animal_breedings.store');

    Route::group(['prefix'=>'{animalbreeding}'],function(){
       
        Route::get('/edit', [BreedingEventsController::class, 'edit'])->name('animal_breedings.edit');
        Route::post('/update', [BreedingEventsController::class, 'update'])->name('animal_breedings.update');

        Route::get('/delete', [BreedingEventsController::class, 'delete'])->name('animal_breedings.delete');
        Route::post('/destroy', [BreedingEventsController::class, 'destroy'])->name('animal_breedings.destroy');

      
    });

   
 
});

















require __DIR__.'/auth.php';
