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
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PurchaseItemsController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseFeedItemsController;
use App\Http\Controllers\DisposeFeedItemsController;
use App\Http\Controllers\PurchaseVaccineItemsController;
use App\Http\Controllers\DisposeVaccineItemsController;
use App\Http\Controllers\FeedConsumeItemsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VaccineConsumeItemsController;
use App\Http\Controllers\PurchaseFeedPaymentController;
use App\Http\Controllers\RetailorOrderController;
use App\Http\Controllers\OrderReviewController;
use App\Http\Controllers\CancelOrderController;
use App\Http\Controllers\VerifyPaymentController;
use App\Http\Controllers\UploadPaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AssignDeliveryPersonController;
use App\Http\Controllers\ReAssignDeliveryPersonController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TaskExecutionController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\MonthlySalaryPaymentController;


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
    
     Route::get('/milkproduction_records/chart', [ProductionMilkController::class, 'monthlyReport'])->name('milk_records_monthly.report');
     Route::get('/milk/animal-yearly-chart', [ProductionMilkController::class, 'animalYearlyChart'])->name('milk.animal_year_chart');

     
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
    

    Route::get('/dispose_milk_records/chart', [DisposeMilkController::class, 'monthlyReport'])->name('dispose_milk_records_monthly.report');

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
    
 
    Route::get('/milk_allocating_for_manufacture/chart', [ProductionSupplyDetailsController::class, 'monthlyReport'])->name('monthly_milk_allocation.report');
    Route::get('/milk-consumption/product-monthly', [ProductionSupplyDetailsController::class, 'productMonthlyConsumption'])->name('milk.consumption.product.monthly');
    Route::get('/reports/animal-milk-usage', [ProductionSupplyDetailsController::class, 'animalMilkUsageReport'])->name('reports.animal_milk_usage_per_month');



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
    
       //for generate reports
  //  Route::get('/total_milkproducts_records/report', [ManufacturerProductController::class, 'report'])->name('total_milkproducts.report');


    // Route to display monthly chart/report
    Route::get('/milk-products/chart', [ManufacturerProductController::class, 'monthlyReport'])->name('milk_products.report');

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
    
    Route::get('/dispose_milk_records/chart', [DisposeMilkProductsController::class, 'monthlyReport'])->name('dispose_milk_products_monthly.report');
  

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
    Route::get('/', [FeedController::class, 'index'])->name('feed_vaccine.list');
    Route::get('/create', [FeedController::class, 'create'])->name('feed_vaccine.create');
    Route::post('/store', [FeedController::class, 'store'])->name('feed_vaccine.store');
    

    Route::group(['prefix'=>'{feed}'],function(){
       
        Route::get('/edit', [FeedController::class, 'edit'])->name('feed_vaccine.edit');
        Route::post('/update', [FeedController::class, 'update'])->name('feed_vaccine.update');

        Route::get('/view', [FeedController::class, 'view'])->name('feed_vaccine.view');

      
        Route::post('/destroy', [FeedController::class, 'destroy'])->name('feed_vaccine.destroy');
    });
 
});

//this below group is used to manage the vaccine details only.just insert the vaccine details to the vaccine table
Route::middleware('auth')->prefix('vaccine_details')->group(function () {
    Route::get('/', [VaccineController::class, 'index'])->name('vaccine.list');
    Route::get('/create', [VaccineController::class, 'create'])->name('vaccine.create');
    Route::post('/store', [VaccineController::class, 'store'])->name('vaccine.store');
    

    Route::group(['prefix'=>'{vaccine}'],function(){
       
        Route::get('/edit', [VaccineController::class, 'edit'])->name('vaccine.edit');
        Route::post('/update', [VaccineController::class, 'update'])->name('vaccine.update');

        Route::get('/view', [VaccineController::class, 'view'])->name('vaccine.view');

      
        Route::post('/destroy', [VaccineController::class, 'destroy'])->name('vaccine.destroy');
    });
 
});


//this below group is used to manage the supplier_feed_vaccine_details.
// this means it handles the feed and vaccine details prodvided by the supplier
Route::middleware('auth')->prefix('supply_feed_vaccine_details')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supply_feed_vaccine.list');
    Route::get('/create', [SupplierController::class, 'create'])->name('supply_feed_vaccine.create');
    Route::post('/store', [SupplierController::class, 'store'])->name('supply_feed_vaccine.store');
    

    Route::group(['prefix'=>'{supplier}'],function(){
       
        Route::get('/edit', [SupplierController::class, 'edit'])->name('supply_feed_vaccine.edit');
        Route::post('/update', [SupplierController::class, 'update'])->name('supply_feed_vaccine.update');

        Route::get('/view', [SupplierController::class, 'view'])->name('supply_feed_vaccine.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });
 
});


//this is used to manage the general manager details
Route::middleware('auth')->prefix('general_manager_details')->group(function () {
  //  Route::get('/', [SupplierController::class, 'index'])->name('supply_feed_vaccine.list');
    Route::get('/create', [GeneralManagerController::class, 'create'])->name('general_manager.create');
  //  Route::post('/store', [SupplierController::class, 'store'])->name('supply_feed_vaccine.store');
    

  /*  Route::group(['prefix'=>'{supplier}'],function(){
       
        Route::get('/edit', [SupplierController::class, 'edit'])->name('supply_feed_vaccine.edit');
        Route::post('/update', [SupplierController::class, 'update'])->name('supply_feed_vaccine.update');

        Route::get('/view', [SupplierController::class, 'view'])->name('supply_feed_vaccine.view');

      
        Route::post('/destroy', [ProductionMilkController::class, 'destroy'])->name('production_milk.destroy');
    });*/
 
});


//this below group is used to manage purchase feed details
Route::middleware('auth')->prefix('purchase_feed_items')->group(function () {

   Route::get('/', [PurchaseFeedItemsController::class, 'index'])->name('purchase_feed_items.list');
  
    Route::get('/create', [PurchaseFeedItemsController::class, 'create'])->name('purchase_feed_items.create');
    Route::post('/store', [PurchaseFeedItemsController::class, 'store'])->name('purchase_feed_items.store');


    Route::get('/report/monthly-feed-purchases', [PurchaseFeedItemsController::class, 'monthlyFeedPurchaseReport'])->name('report.monthly_feed_purchase');
    Route::get('/reports/monthly-feed-spending', [PurchaseFeedItemsController::class, 'monthlyFeedPurchaseCostReport'])->name('reports.feed_spending_for_each_product');



    Route::group(['prefix'=>'{purchasefeeditem}'],function(){
       
        Route::get('/edit', [PurchaseFeedItemsController::class, 'edit'])->name('purchase_feed_items.edit');
        Route::post('/update', [PurchaseFeedItemsController::class, 'update'])->name('purchase_feed_items.update');

        Route::get('/view', [PurchaseFeedItemsController::class, 'view'])->name('purchase_feed_items.view');
        Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');

      
    });

   
});



//this below group is used to manage purchase feed payments details
Route::middleware('auth')->prefix('purchase_feed_items_payments')->group(function () {

    Route::get('/', [PurchaseFeedPaymentController::class, 'index'])->name('purchase_feed_payments.list');
   
     Route::get('/create', [PurchaseFeedPaymentController::class, 'create'])->name('purchase_feed_payments.create');
     Route::post('/store', [PurchaseFeedPaymentController::class, 'store'])->name('purchase_feed_payments.store');

     Route::get('/payment-slip/{id}', [PurchaseFeedPaymentController::class, 'downloadPaymentSlip'])->name('payment.slip.download');

 
     Route::get('/get-purchase-amount/{id}', [PurchaseFeedPaymentController::class, 'getPurchaseAmount']);

     Route::group(['prefix'=>'{purchasefeedpayment}'],function(){
        
         Route::get('/edit', [PurchaseFeedPaymentController::class, 'edit'])->name('purchase_feed_payments.edit');
         Route::post('/update', [PurchaseFeedPaymentController::class, 'update'])->name('purchase_feed_payments.update');
 
         Route::get('/view', [PurchaseFeedPaymentController::class, 'view'])->name('purchase_feed_payments.view');
         Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');
 
       
     });
 
    
 });


//this below group is used to manage retailor order details
Route::middleware('auth')->prefix('retailor_order_items')->group(function () {

    Route::get('/', [RetailorOrderController::class, 'index'])->name('retailor_order_items.list');
   
     Route::get('/create', [RetailorOrderController::class, 'create'])->name('retailor_order_items.create');
     Route::post('/store', [RetailorOrderController::class, 'store'])->name('retailor_order_items.store');

    // Route::get('/payment-slip/{id}', [PurchaseFeedPaymentController::class, 'downloadPaymentSlip'])->name('payment.slip.download');

 
    // Route::get('/get-purchase-amount/{id}', [PurchaseFeedPaymentController::class, 'getPurchaseAmount']);

     Route::group(['prefix'=>'{retailororder}'],function(){
        
         Route::get('/edit', [RetailorOrderController::class, 'edit'])->name('retailor_order_items.edit');
         Route::post('/update', [RetailorOrderController::class, 'update'])->name('retailor_order_items.update');
 
         Route::get('/view', [RetailorOrderController::class, 'view'])->name('retailor_order_items.view');
      //   Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');
 
       
     });
 
    
 });


 //this below group is used to manage tasks details
Route::middleware('auth')->prefix('tasks')->group(function () {

    Route::get('/', [TasksController::class,'index'])->name('tasks.list');
   
     Route::get('/create', [TasksController::class, 'create'])->name('tasks.create');
     Route::post('/store', [TasksController::class, 'store'])->name('tasks.store');

    // Route::get('/payment-slip/{id}', [PurchaseFeedPaymentController::class, 'downloadPaymentSlip'])->name('payment.slip.download');

 
    // Route::get('/get-purchase-amount/{id}', [PurchaseFeedPaymentController::class, 'getPurchaseAmount']);

     Route::group(['prefix'=>'{task}'],function(){
        
         Route::get('/edit', [TasksController::class, 'edit'])->name('tasks.edit');
         Route::post('/update', [TasksController::class, 'update'])->name('tasks.update');
 
         Route::get('/view', [TasksController::class, 'view'])->name('tasks.view');
      //   Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');
 
       
     });
 
    
 });


 //this below group is used to manage tasks assignment details
 Route::middleware('auth')->prefix('tasks_assignment')->group(function () {

        Route::get('/', [TaskAssignmentController::class,'index'])->name('tasks_assignment.list');
   
        Route::get('/create', [TaskAssignmentController::class, 'create'])->name('tasks_assignment.create');
        Route::post('/store', [TaskAssignmentController::class, 'store'])->name('tasks_assignment.store');

        Route::group(['prefix'=>'{taskassignment}'],function(){
    
 
        Route::get('/view', [TaskAssignmentController::class, 'view'])->name('tasks_assignment.view');
        Route::get('/view_reassign_form', [TaskAssignmentController::class, 'showReassignForm'])->name('task-assignments.reassign-form');
        Route::put('/reassign', [TaskAssignmentController::class, 'reassign'])->name('task-assignments.reassign');
 
       
     });
 
    
 });





//this below group is used to manage tasks execution details//task execution does not have table
Route::middleware('auth')->prefix('tasks_execution')->group(function () {

    Route::get('/', [TaskExecutionController::class,'myTasks'])->name('tasks_execution.list');

    Route::post('/tasks/{id}/start', [TaskExecutionController::class, 'startTask'])->name('task_execution.start');
    Route::post('/tasks/{id}/submit', [TaskExecutionController::class, 'submitForApproval'])->name('task_execution.complete');
    Route::post('/tasks/{id}/approve', [TaskExecutionController::class, 'approveTask'])->name('task_execution.approve');
  
    Route::put('/task-assignments/{id}/reassign', [TaskExecutionController::class, 'reassign'])->name('task-assignments.reassign');


    Route::post('/task/{id}/reject', [TaskExecutionController::class, 'reject'])->name('task_execution.reject');

  
  //  Route::get('/create', [TaskAssignmentController::class, 'create'])->name('tasks_assignment.create');
  //  Route::post('/store', [TaskAssignmentController::class, 'store'])->name('tasks_assignment.store');

   // Route::get('/payment-slip/{id}', [PurchaseFeedPaymentController::class, 'downloadPaymentSlip'])->name('payment.slip.download');


   // Route::get('/get-purchase-amount/{id}', [PurchaseFeedPaymentController::class, 'getPurchaseAmount']);

    //Route::group(['prefix'=>'{taskassignment}'],function(){
       
    //    Route::get('/edit', [TasksController::class, 'edit'])->name('tasks.edit');
   //     Route::post('/update', [TasksController::class, 'update'])->name('tasks.update');

    //   Route::get('/view', [TaskAssignmentController::class, 'view'])->name('tasks_assignment.view');
     //   Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');

      
   // });

   
});


//this below group is used to manage salry for each role
 Route::middleware('auth')->prefix('salary')->group(function () {

        Route::get('/', [SalaryController::class,'index'])->name('salary.list');
   
        Route::get('/create', [SalaryController::class, 'create'])->name('salary.create');
        Route::post('/store', [SalaryController::class, 'store'])->name('salary.store');

        Route::group(['prefix'=>'{salary}'],function(){
    
            Route::get('/edit', [SalaryController::class, 'edit'])->name('salary.edit');
            Route::post('/update', [SalaryController::class, 'update'])->name('salary.update');
 
            Route::get('/view', [SalaryController::class, 'view'])->name('salary.view');
      //   Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');
 
       
     });
 
    
 });


//this below group is used to manage monthly salary payment for each role
 Route::middleware('auth')->prefix('monthly_salary')->group(function () {

     //   Route::get('/', [SalaryController::class,'index'])->name('salary.list');
   
        Route::get('/create', [MonthlySalaryPaymentController::class, 'create'])->name('monthly_salary_payment.create');
        Route::get('/get-eligible-employees', [MonthlySalaryPaymentController::class, 'getEligibleEmployees']);

        
     //   Route::post('/store', [SalaryController::class, 'store'])->name('salary_payment.store');

     //   Route::group(['prefix'=>'{salary}'],function(){
    
        //    Route::get('/edit', [SalaryController::class, 'edit'])->name('salary.edit');
         //   Route::post('/update', [SalaryController::class, 'update'])->name('salary.update');
 
        //    Route::get('/view', [SalaryController::class, 'view'])->name('salary.view');
      //   Route::post('/destroy', [PurchaseFeedItemsController::class, 'destroy'])->name('purchase_feed_items.destroy');
 
       
     //});
 
    
 });





//this below group is used to manage order review details
Route::middleware('auth')->prefix('order_review_details')->group(function () {

    
    Route::get('/manager/orders/{order}/review', [OrderReviewController::class, 'review'])->name('manager.orders.review');
   
    Route::post('/manager/orders/{order}/approve', [OrderReviewController::class, 'approveOrder'])->name('manager.orders.approve');

    Route::patch('/manager/orders/{order}/reject', [OrderReviewController::class, 'reject'])->name('manager.orders.reject');

  
     
    
 });

 
 //this below group is used to manage cancel order details
Route::middleware('auth')->prefix('cancel_order_details')->group(function () {

    
    Route::post('/orders/{order}/cancel-after-approval', [CancelOrderController::class, 'cancelOrderAfterApproved'])->name('order.cancel_after_approval');
   
    Route::post('orders/{order}/cancel-before-approval', [CancelOrderController::class, 'cancelOrderBeforeApproved'])->name('order.cancel_before_approval');

   
     
    
 });

//this below group is used to invoice for order details
Route::middleware('auth')->prefix('invoice_for_order_details')->group(function () {

    
    Route::get('/retailor_orders/{order}/invoice', [InvoiceController::class, 'generateInvoice'])->name('retailor_order.invoice');
      
    
 });



 //this below group is used to upload payment details
Route::middleware('auth')->prefix('upload_payment_details')->group(function () {

    Route::get('retailor/upload_payment/{order}/create', [UploadPaymentController::class, 'create'])->name('upload_payment.receipt.create');

    Route::post('retailor/upload_payment/{order}/store', [UploadPaymentController::class, 'store'])->name('upload_payment.receipt.store');

    Route::get('retailor/upload_payment/{order}/edit', [UploadPaymentController::class, 'edit'])->name('upload_payment.receipt.edit');
    
    
     
    
 });

//this below group is used to assign delivery person details
Route::middleware('auth')->prefix('assign_delivery_person')->group(function () {

    Route::get('assign/{order}/create', [AssignDeliveryPersonController::class, 'showDeliveryPersonForm'])->name('assign_delivery_person.create');

    Route::post('assign/{order}/store', [AssignDeliveryPersonController::class, 'assignDeliveryPerson'])->name('assign_delivery_person.store');

   // Route::get('retailor/upload_payment/{order}/edit', [UploadPaymentController::class, 'edit'])->name('upload_payment.receipt.edit');
    

 });


//this below group is used to re-assign delivery person details
Route::middleware('auth')->prefix('re-assign_delivery_person')->group(function () {

    Route::get('re-assign/{order}/create', [ReAssignDeliveryPersonController::class, 'showReAssignDeliveryPersonForm'])->name('re_assign_delivery_person.create');

    Route::post('re-assign/{order}/store', [ReAssignDeliveryPersonController::class, 'ReAssignDeliveryPerson'])->name('re_assign_delivery_person.store');

 });




//this below group is used to marked for the delivery products are ready  for out of the farm
Route::middleware('auth')->prefix('products_out_for_the_farm_to_delivery')->group(function () {

    Route::post('/orders/{id}/start-delivery', [DeliveryController::class, 'startDelivery'])->name('orders.startDelivery');

 });

 //this below group is used to when delivery person successfully deliver the products and click the delivered button
Route::middleware('auth')->prefix('successful_deliver_the_prodcuts')->group(function () {

    Route::post('/orders/{id}/successful_delivery', [DeliveryController::class, 'markAsDelivered'])->name('orders.successful_delivery');

 });



 //this below group is used to verify the payment details
Route::middleware('auth')->prefix('verify_payment_details')->group(function () {

 
    Route::get('/verify_payment/{order}/show',[VerifyPaymentController::class,'show'])->name('verify_payment.view');

    Route::post('/verify_payment/{order}/accept', [VerifyPaymentController::class, 'verifyPaymentAccept'])->name('verify_payment.receipt.accept');

    Route::post('/verify_payment/{order}/reject', [VerifyPaymentController::class, 'verifyPaymentReject'])->name('verify_payment.receipt.reject');
    
//    Route::post('/orders/{order}/cancel-after-approval', [VerifyPaymentController::class, 'cancelOrderAfterApproved'])->name('order.cancel_after_approval');
   
 //   Route::post('orders/{order}/cancel-before-approval', [CancelOrderController::class, 'cancelOrderBeforeApproved'])->name('order.cancel_before_approval');

   
     
 });




//this below group is used to manage dispose feed item details
Route::middleware('auth')->prefix('dispose_feed_items')->group(function () {

    Route::get('/', [DisposeFeedItemsController::class, 'index'])->name('dispose_feed_items.list');
   
     Route::get('/create', [DisposeFeedItemsController::class, 'create'])->name('dispose_feed_items.create');
     Route::post('/store', [DisposeFeedItemsController::class, 'store'])->name('dispose_feed_items.store');
 
    Route::get('/reports/feed-disposal', [DisposeFeedItemsController::class, 'monthlyFeedDisposalReport'])->name('feed.disposal.report');


     Route::group(['prefix'=>'{disposefeeditem}'],function(){
        
         Route::get('/edit', [DisposeFeedItemsController::class, 'edit'])->name('dispose_feed_items.edit');
         Route::post('/update', [DisposeFeedItemsController::class, 'update'])->name('dispose_feed_items.update');
 
         Route::get('/view', [DisposeFeedItemsController::class, 'view'])->name('dispose_feed_items.view');
         Route::post('/destroy', [DisposeFeedItemsController::class, 'destroy'])->name('dispose_feed_items.destroy');
 
       
     });
 
 
    
  
 });


 //this below group is used to manage purchase vaccine details
Route::middleware('auth')->prefix('purchase_vaccine_items')->group(function () {

    Route::get('/', [PurchaseVaccineItemsController::class, 'index'])->name('purchase_vaccine_items.list');
   
     Route::get('/create', [PurchaseVaccineItemsController::class, 'create'])->name('purchase_vaccine_items.create');
     Route::post('/store', [PurchaseVaccineItemsController::class, 'store'])->name('purchase_vaccine_items.store');
 
     Route::group(['prefix'=>'{purchasevaccineitem}'],function(){
        
         Route::get('/edit', [PurchaseVaccineItemsController::class, 'edit'])->name('purchase_vaccine_items.edit');
         Route::post('/update', [PurchaseVaccineItemsController::class, 'update'])->name('purchase_vaccine_items.update');
 
         Route::get('/view', [PurchaseVaccineItemsController::class, 'view'])->name('purchase_vaccine_items.view');
         Route::post('/destroy', [PurchaseVaccineItemsController::class, 'destroy'])->name('purchase_vaccine_items.destroy');
 
       
     });
 
 
    
  
 });


 //this below group is used to manage dispose vaccine item details
Route::middleware('auth')->prefix('dispose_vaccine_items')->group(function () {

    Route::get('/', [DisposeVaccineItemsController::class, 'index'])->name('dispose_vaccine_items.list');
   
     Route::get('/create', [DisposeVaccineItemsController::class, 'create'])->name('dispose_vaccine_items.create');
     Route::post('/store', [DisposeVaccineItemsController::class, 'store'])->name('dispose_vaccine_items.store');
 
     Route::group(['prefix'=>'{disposevaccineitem}'],function(){
        
        Route::get('/edit', [DisposeVaccineItemsController::class, 'edit'])->name('dispose_vaccine_items.edit');
        Route::post('/update', [DisposeVaccineItemsController::class, 'update'])->name('dispose_vaccine_items.update');
 
        Route::get('/view', [DisposeVaccineItemsController::class, 'view'])->name('dispose_vaccine_items.view');
        Route::post('/destroy', [DisposeVaccineItemsController::class, 'destroy'])->name('dispose_vaccine_items.destroy');
 
       
     });
 
 
    
  
 });


  //this below group is used to manage feed consumption details
Route::middleware('auth')->prefix('feed_consume_items_by_animals')->group(function () {

Route::get('/', [FeedConsumeItemsController::class, 'index'])->name('feed_consume_items.list');
   
     Route::get('/create', [FeedConsumeItemsController::class, 'create'])->name('feed_consume_items.create');
     Route::post('/store', [FeedConsumeItemsController::class, 'store'])->name('feed_consume_items.store');
 
     Route::group(['prefix'=>'{feedconsumeitem}'],function(){
        
        Route::get('/edit', [FeedConsumeItemsController::class, 'edit'])->name('feed_consume_items.edit');
        Route::post('/update', [FeedConsumeItemsController::class, 'update'])->name('feed_consume_items.update');
 
        Route::get('/view', [FeedConsumeItemsController::class, 'view'])->name('feed_consume_items.view');
        Route::post('/destroy', [FeedConsumeItemsController::class, 'destroy'])->name('feed_consume_items.destroy');
 
       
     });
 
 
    
  
 });

  //this below group is used to manage vaccine consumption details
Route::middleware('auth')->prefix('vaccine_consume_items_by_animals')->group(function () {

         Route::get('/', [VaccineConsumeItemsController::class, 'index'])->name('vaccine_consume_items.list');
       
         Route::get('/create', [VaccineConsumeItemsController::class, 'create'])->name('vaccine_consume_items.create');
         Route::post('/store', [VaccineConsumeItemsController::class, 'store'])->name('vaccine_consume_items.store');
     
         Route::group(['prefix'=>'{vaccineconsumeitem}'],function(){
            
            Route::get('/edit', [VaccineConsumeItemsController::class, 'edit'])->name('vaccine_consume_items.edit');
            Route::post('/update', [VaccineConsumeItemsController::class, 'update'])->name('vaccine_consume_items.update');
     
            Route::get('/view', [VaccineConsumeItemsController::class, 'view'])->name('vaccine_consume_items.view');
            Route::post('/destroy', [VaccineConsumeItemsController::class, 'destroy'])->name('vaccine_consume_items.destroy');
     
           
         });
     
     
        
      
     });


  //this below group is used to manage veterinarians schedule details
Route::middleware('auth')->prefix('veterinarians_schedule')->group(function () {

    Route::get('/', [AppointmentController::class, 'index'])->name('appointment.list');
       
         Route::get('/create', [AppointmentController::class, 'create'])->name('appointment.create');
         Route::post('/store', [AppointmentController::class, 'store'])->name('appointment.store');
     
         Route::group(['prefix'=>'{appointment}'],function(){
            
            Route::get('/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
            Route::post('/update', [AppointmentController::class, 'update'])->name('appointment.update');
     
            Route::get('/view', [AppointmentController::class, 'view'])->name('appointment.view');
            Route::post('/destroy', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
     
           
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
