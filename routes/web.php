<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedingEventsController;
use App\Http\Controllers\AnimalCalvingsController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\PregnanciesController;

use App\Http\Controllers\VeterinarianController;
use App\Http\Controllers\RetailerController;

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

        Route::get('/view', [AnimalCalvingsController::class, 'view'])->name('animal_calvings.view');
        Route::post('/destroy', [AnimalCalvingsController::class, 'destroy'])->name('animal_calvings.destroy');

      
    });

  
    Route::group(['prefix'=>'{calfId}'],function(){
       
        Route::get('/details', [AnimalCalvingsController::class, 'getCalfDetails']);

      
    });

   
 
});

//this below group is used to animal_breedings details
Route::middleware('auth')->prefix('animal_pregnancies')->group(function () {

    Route::get('/', [PregnanciesController::class, 'index'])->name('animal_pregnancies.list');
  
    Route::get('/create', [PregnanciesController::class, 'create'])->name('animal_pregnancies.create');
    Route::post('/store', [PregnanciesController::class, 'store'])->name('animal_pregnancies.store');

    Route::group(['prefix'=>'{animalbreeding}'],function(){
       
        Route::get('/view', [PregnanciesController::class, 'view'])->name('animal_pregnancies.view');
        Route::get('/edit', [PregnanciesController::class, 'edit'])->name('animal_pregnancies.edit');
        Route::post('/update', [PregnanciesController::class, 'update'])->name('animal_pregnancies.update');

        Route::get('/delete', [PregnanciesController::class, 'delete'])->name('animal_pregnancies.delete');
        Route::post('/destroy', [PregnanciesController::class, 'destroy'])->name('animal_pregnancies.destroy');

      
    });
 
});


Route::middleware('auth')->prefix('animal_breedings')->group(function () {

    Route::get('/', [BreedingEventsController::class, 'index'])->name('animal_breedings.list');
  
    Route::get('/create', [BreedingEventsController::class, 'create'])->name('animal_breedings.create');
    Route::post('/store', [BreedingEventsController::class, 'store'])->name('animal_breedings.store');

    Route::group(['prefix'=>'{animalbreeding}'],function(){
       
        Route::get('/view', [BreedingEventsController::class, 'view'])->name('animal_breedings.view');
        Route::get('/edit', [BreedingEventsController::class, 'edit'])->name('animal_breedings.edit');
        Route::post('/update', [BreedingEventsController::class, 'update'])->name('animal_breedings.update');

        Route::get('/delete', [BreedingEventsController::class, 'delete'])->name('animal_breedings.delete');
        Route::post('/destroy', [BreedingEventsController::class, 'destroy'])->name('animal_breedings.destroy');

      
    });
 
});





Route::middleware('auth')->prefix('users')->group(function () {

    Route::get('/', [UserRegisterController::class, 'index'])->name('users.list');
  
    Route::get('/create', [UserRegisterController::class, 'create'])->name('users.create');
    Route::post('/store', [UserRegisterController::class, 'store'])->name('users.store');

    

    
    //this is for the veterinarian group
    Route::group(['prefix'=>'veterinarian_list'],function(){

        Route::get('/', [UserRegisterController::class, 'veterinarian_index'])->name('veterinarians.list');

        Route::group(['prefix'=>'{veterinarian}'],function(){

            Route::get('/view', [VeterinarianController::class, 'view'])->name('veterinarians.view');

            Route::get('/edit', [VeterinarianController::class, 'edit'])->name('veterinarians.edit');
            Route::post('/update', [VeterinarianController::class, 'update'])->name('veterinarians.update');
    
            
            Route::post('/destroy', [VeterinarianController::class, 'destroy'])->name('veterinarians.destroy');
    

        });


       
      
    });

    //this is for the retailer group
    Route::group(['prefix'=>'retailer_list'],function(){

        Route::get('/', [UserRegisterController::class, 'retailer_index'])->name('retailers.list');

        Route::group(['prefix'=>'{retailer}'],function(){

            Route::get('/view', [RetailerController::class, 'view'])->name('retailers.view');

            Route::get('/edit', [RetailerController::class, 'edit'])->name('retailers.edit');
            Route::post('/update', [RetailerController::class, 'update'])->name('retailers.update');

        
            Route::post('/destroy', [RetailerController::class, 'destroy'])->name('retailers.destroy');

        });

        

      
    });




});






require __DIR__.'/auth.php';
