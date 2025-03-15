<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedingEventsController;
use App\Http\Controllers\AnimalCalvingsController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\PregnanciesController;
use App\Http\Controllers\ProductionMilkController;
use App\Http\Controllers\VeterinarianController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\ProductionSupplyDetailsController;
use App\Http\Controllers\MilkProductController;
use App\Http\Controllers\ManufacturerProductController;
use App\Http\Controllers\DisposeMilkController;
use App\Http\Controllers\DisposeMilkProductsController;
use App\Http\Controllers\FeedVaccineDetailsController;
use App\Http\Controllers\PurchaseItemsController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\SupplierController;

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
    

    Route::get('/filter', [AnimalController::class, 'filterByType'])->name('animals.filter');

    Route::group(['prefix'=>'{animaldetail}'],function(){
       
        Route::get('/edit', [AnimalController::class, 'edit'])->name('animal.edit');
        Route::post('/update', [AnimalController::class, 'update'])->name('animal.update');

        Route::get('/view', [AnimalController::class, 'view'])->name('animal.view');

      
        Route::post('/destroy', [AnimalController::class, 'destroy'])->name('animal.destroy');
    });
 
});

//this below group is used to manage about milk_production_details
Route::middleware('auth')->prefix('milk_production_details')->group(function () {
    Route::get('/', [ProductionMilkController::class, 'index'])->name('production_milk.list');
    Route::get('/create', [ProductionMilkController::class, 'create'])->name('production_milk.create');
    Route::post('/store', [ProductionMilkController::class, 'store'])->name('production_milk.store');
    

    Route::group(['prefix'=>'{productionmilk}'],function(){
       
        Route::get('/edit', [ProductionMilkController::class, 'edit'])->name('production_milk.edit');
        Route::post('/update', [ProductionMilkController::class, 'update'])->name('production_milk.update');

        Route::get('/view', [ProductionMilkController::class, 'view'])->name('production_milk.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });
 
});

/*
//this below group is used to manage purchase details
Route::middleware('auth')->prefix('feed_purchase_details')->group(function () {
    //Route::get('/', [ProductionMilkController::class, 'index'])->name('production_milk.list');
    Route::get('/create', [PurchaseItemsController::class, 'create'])->name('purchase_items.create');
    Route::post('/store', [ProductionMilkController::class, 'store'])->name('production_milk.store');
    

    Route::group(['prefix'=>'{productionmilk}'],function(){
       
        Route::get('/edit', [ProductionMilkController::class, 'edit'])->name('production_milk.edit');
        Route::post('/update', [ProductionMilkController::class, 'update'])->name('production_milk.update');

        Route::get('/view', [ProductionMilkController::class, 'view'])->name('production_milk.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });
 
});*/



//this below group is used to dispose milk
Route::middleware('auth')->prefix('milk_dispose')->group(function () {
    Route::get('/', [DisposeMilkController::class, 'index'])->name('dispose_milk.list');
    Route::get('/create', [DisposeMilkController::class, 'create'])->name('dispose_milk.create');
    Route::post('/store', [DisposeMilkController::class, 'store'])->name('dispose_milk.store');
    

    Route::group(['prefix'=>'{disposeMilk}'],function(){
       
        Route::get('/edit', [DisposeMilkController::class, 'edit'])->name('dispose_milk.edit');
        Route::post('/update', [DisposeMilkController::class, 'update'])->name('dispose_milk.update');

        Route::get('/view', [DisposeMilkController::class, 'view'])->name('dispose_milk.view');

      
        Route::post('/destroy', [DisposeMilkController::class, 'destroy'])->name('dispose_milk.destroy');
    });

    Route::group(['prefix'=>'{productionMilkId}'],function(){
       
        Route::get('/details', [DisposeMilkController::class, 'getStockQuantityDetails']);

      
    });
 
});




//this group is used to input the milk product details
Route::middleware('auth')->prefix('milk_product_details')->group(function () {
    Route::get('/', [MilkProductController::class, 'index'])->name('milk_product.list');
    Route::get('/create', [MilkProductController::class, 'create'])->name('milk_product.create');
    Route::post('/store', [MilkProductController::class, 'store'])->name('milk_product.store');
    

    Route::group(['prefix'=>'{milkProduct}'],function(){
       
        Route::get('/edit', [MilkProductController::class, 'edit'])->name('milk_product.edit');
        Route::post('/update', [MilkProductController::class, 'update'])->name('milk_product.update');

        Route::get('/view', [MilkProductController::class, 'view'])->name('milk_product.view');

      
        Route::post('/destroy', [MilkProductController::class, 'destroy'])->name('milk_product.destroy');
    });
 
});

//this group is about amount of milk allocated for the milk products
Route::middleware('auth')->prefix('details_about_the_milk_allocated_for_production')->group(function () {
    Route::get('/', [ProductionSupplyDetailsController::class, 'index'])->name('milk_allocated_for_manufacturing.index');
    Route::get('/create', [ProductionSupplyDetailsController::class, 'create'])->name('milk_allocated_for_manufacturing.create');
    Route::post('/store', [ProductionSupplyDetailsController::class, 'store'])->name('milk_allocated_for_manufacturing.store');
    

  

    Route::group(['prefix'=>'{productionSupplyDetails}'],function(){
       
        Route::get('/edit', [ProductionSupplyDetailsController::class, 'edit'])->name('milk_allocated_for_manufacturing.edit');
        Route::post('/update', [ProductionSupplyDetailsController::class, 'update'])->name('milk_allocated_for_manufacturing.update');

        Route::get('/view', [ProductionSupplyDetailsController::class, 'view'])->name('milk_allocated_for_manufacturing.view');

      
        Route::post('/destroy', [ProductionSupplyDetailsController::class, 'destroy'])->name('milk_product.destroy');
    });
 
});


//this group is used for manufacture product details
Route::middleware('auth')->prefix('milk_products_manufacture_details')->group(function () {
    Route::get('/', [ManufacturerProductController::class, 'index'])->name('manufacture_product.index');
    Route::get('/create', [ManufacturerProductController::class, 'create'])->name('manufacture_product.create');
    Route::post('/store', [ManufacturerProductController::class, 'store'])->name('manufacture_product.store');
    

  

    Route::group(['prefix'=>'{manufacturerProduct}'],function(){
       
        Route::get('/edit', [ManufacturerProductController::class, 'edit'])->name('manufacture_product.edit');
        Route::post('/update', [ManufacturerProductController::class, 'update'])->name('manufacture_product.update');

        Route::get('/view', [ManufacturerProductController::class, 'view'])->name('manufacture_product.view');

      
        Route::post('/destroy', [ProductionSupplyDetailsController::class, 'destroy'])->name('milk_production.destroy');
    });
 
});

//this group is used to dipsose the milk products
Route::middleware('auth')->prefix('dispose_milk_products')->group(function () {
    Route::get('/', [DisposeMilkProductsController::class, 'index'])->name('dispose_milk_product.index');
    Route::get('/create', [DisposeMilkProductsController::class, 'create'])->name('dispose_milk_product.create');
    Route::post('/store', [DisposeMilkProductsController::class, 'store'])->name('dispose_milk_product.store');
    

  

    Route::group(['prefix'=>'{disposeMilkProducts}'],function(){
       
        Route::get('/edit', [DisposeMilkProductsController::class, 'edit'])->name('dispose_milk_product.edit');

        Route::post('/update', [DisposeMilkProductsController::class, 'update'])->name('dispose_milk_product.update');

        Route::get('/view', [DisposeMilkProductsController::class, 'view'])->name('dispose_milk_product.view');

      
        Route::post('/destroy', [DisposeMilkProductsController::class, 'destroy'])->name('dispose_milk_product.destroy');
    });


    Route::group(['prefix'=>'{productionMilkId}'],function(){
       
        Route::get('/details', [DisposeMilkProductsController::class, 'getStockQuantityDetails']);

      
    });


    
 
});


//this below group is used to manage the feed  details only.just insert the feed details to feed table
Route::middleware('auth')->prefix('feed_details')->group(function () {
    Route::get('/', [FeedVaccineDetailsController::class, 'index'])->name('feed_vaccine.list');
    Route::get('/create', [FeedVaccineDetailsController::class, 'create'])->name('feed_vaccine.create');
    Route::post('/store', [FeedVaccineDetailsController::class, 'store'])->name('feed_vaccine.store');
    

    Route::group(['prefix'=>'{productionmilk}'],function(){
       
        Route::get('/edit', [ProductionMilkController::class, 'edit'])->name('production_milk.edit');
        Route::post('/update', [ProductionMilkController::class, 'update'])->name('production_milk.update');

        Route::get('/view', [ProductionMilkController::class, 'view'])->name('production_milk.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });
 
});

//this below group is used to manage the vaccine details only.just insert the vaccine details to the vaccine table
Route::middleware('auth')->prefix('vaccine_details')->group(function () {
    Route::get('/', [VaccineController::class, 'index'])->name('vaccine.list');
    Route::get('/create', [VaccineController::class, 'create'])->name('vaccine.create');
    Route::post('/store', [VaccineController::class, 'store'])->name('vaccine.store');
    

    Route::group(['prefix'=>'{productionmilk}'],function(){
       
        Route::get('/edit', [ProductionMilkController::class, 'edit'])->name('production_milk.edit');
        Route::post('/update', [ProductionMilkController::class, 'update'])->name('production_milk.update');

        Route::get('/view', [ProductionMilkController::class, 'view'])->name('production_milk.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });
 
});


//this below group is used to manage the supplier_feed_vaccine_details.
// this means it handles the feed and vaccine details prodvided by the supplier
Route::middleware('auth')->prefix('supply_feed_vaccine_details')->group(function () {
    Route::get('/', [VaccineController::class, 'index'])->name('vaccine.list');
    Route::get('/create', [SupplierController::class, 'create'])->name('supply_feed_vaccine.create');
    Route::post('/store', [SupplierController::class, 'store'])->name('supply_feed_vaccine.store');
    

    Route::group(['prefix'=>'{productionmilk}'],function(){
       
        Route::get('/edit', [ProductionMilkController::class, 'edit'])->name('production_milk.edit');
        Route::post('/update', [ProductionMilkController::class, 'update'])->name('production_milk.update');

        Route::get('/view', [ProductionMilkController::class, 'view'])->name('production_milk.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
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

    Route::group(['prefix'=>'{pregnancie}'],function(){
       
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
