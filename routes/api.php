<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\DescrptionController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedPharmacyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\OrderUserDetailController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\WalletphController;
use App\Http\Controllers\WalletuserController;
use App\Http\Controllers\WalletWarehouseController;
use App\Http\Controllers\WarehouseController;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\WalletWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
        //..................admin.............................................
Route::post('/adminregister',[AdminController::class,'register']);

Route::post('/adminlogin',[AdminController::class,'login']);

Route::group(['middleware' => ['auth']], function() {
    /**
    * Logout Route
    */
    Route::get('/adminlogout',[AdminController::class,'perform']);
 });

 Route::put('/adminupdate',[AdminController::class,'update']);
   
 //..........................user................................................

Route::post('/userregister',[Usercontroller::class,'register']);


Route::post('/userlogin',[Usercontroller::class,'login']);

Route::resource('/users',Usercontroller::class);

Route::put('/usersupdate',[Usercontroller::class,'update']);

Route::delete('/usersdelete',[Usercontroller::class,'destroy']);

Route::group(['middleware' => ['auth']], function() {
    /**
    * Logout Route
    */
    Route::get('/userlogout',[AdminController::class,'perform']);
 });

////////////////////////////View product orders/////////////////// 
Route::post('/get/users/order/product',[OrderUserDetailController::class,'OrderproductForUsers']);

////////////////////////////View medicien orders/////////////////// 

Route::post('/get/users/order/detail',[OrderUserDetailController::class,'OrderMedForUsers']);

/////////////////////////view wallet///////////////////////////////////

Route::get('/wallet/warehouse',[WalletWarehouseController::class,'show']);

/////////////////////////view order///////////////////////////
Route::get('/view/order/users',[ProductOrderController::class,'showoforuser']);

//////////////////////Confirm the order//////////////////////

Route::post('/order/accepte/user',[ProductOrderController::class,'accepteforuser']);


/////////////////////add order for user///////////////////////

Route::post('/add/order/user',[ProductOrderController::class,'store']);

/////////////////////delete order for user///////////////////////


Route::delete('/order/user/delete',[ProductOrderController::class,'destroy']);


//////////////////////add order Detail/////////////////
Route::post('/add/order/product',[OrderUserDetailController::class,'storeproduct']);

//////////////////////delete order Detail/////////////////

Route::delete('/delete/order/product',[OrderUserDetailController::class,'destroy']);



//...................warehouse....................................................

 Route::post('/houseregister',[WarehouseController::class,'register']);

 Route::post('/houselogin',[WarehouseController::class,'login']);

 Route::resource('/warehouse',WarehouseController::class);

 Route::put('/houseupdate',[WarehouseController::class,'update']);

 Route::post('/get/warehouse/city',[WarehouseController::class,'show']);


 Route::delete('/housesdelete',[WarehouseController::class,'destroy']);
 
Route::group(['middleware' => ['auth']], function() {
    /**
    * Logout Route
    */
    Route::get('/warehouselogout',[WarehouseController::class,'perform']);
 });
 Route::post('/warehouseinfo',[WarehouseController::class,'information']);

  // create medcine in warehouse 
 Route::post('/addmed',[MedicineController::class,'createmed']);

  //.................wallet warehouse...........................................................


  Route::post('/walletwarehouse/reset_balance', [WalletwarehouseController::class,'resetBalanceWarehouse']);


//..................pharamcy.......................................................
 Route::post('/pharmacyregister',[PharmacyController::class,'register']);

 Route::post('/pharmacy/city',[PharmacyController::class,'show']);


 Route::post('/pharmacylogin',[PharmacyController::class,'login']);

 Route::resource('/pharmacy',PharmacyController::class);

 Route::put('/pharmacy',[PharmacyController::class,'update']);

 Route::delete('/pharmacy',[PharmacyController::class,'destroy']);

 Route::post('/pharmacy/med',[PharmacyController::class,'getmed']);

 Route::get('/get/order/pharmacy',[OrderController::class,'showorders']);

 Route::get('/get/order/detail/pharmacy',[OrderDetailController::class,'showforpharmacy']);

  // create order for pharamcy 

 Route::post('/addorder',[OrderController::class,'store']);
 
// add medcine to order for pharamcy

Route::resource('/order/details',OrderDetailController::class);
  // confrim  order for pharmacy
 Route::post('/order/acceptforpharmacy',[OrderController::class,'acceptforpharmacy']);

 

 //.................wallet pharmacy...........................................................
 Route::put('/wallet',[WalletphController::class,'store']);

 Route::get('/wallet',[WalletphController::class,'show']);

 Route::post('/walletph/reset_balance', [WalletphController::class,'resetBalancePh']);






//..................medicinecrud for warehouse.......................................................


Route::get('/exp',[MedicineController::class,'exp']);



 Route::post('/med',[MedicineController::class,'show']);

 Route::put('/med',[MedicineController::class,'update']);
 
 Route::delete('/med',[MedicineController::class,'destroy']);

 Route::get('/notification',[MedicineController::class,'notification']);


 Route::post('/addquantity',[MedicineController::class,'edit']);


//...................wallet ueser........................

Route::put('/walletuser',[WalletuserController::class,'store']);

Route::resource('/walletuser',WalletuserController::class);

Route::post('/walletusers/reset_balance', [WalletuserController::class,'resetBalanceUser']);

//......................order..................................
Route::post('/all/order',[OrderController::class,'index']);

Route::post('/all/Confirmed/orders',[OrderController::class,'Confirmedorders']);

Route::post('/all/done/orders',[OrderController::class,'done']);





Route::post('/order',[OrderController::class,'show']);

Route::delete('/order/delete',[OrderController::class,'destroy']);






 // 
Route::post('/getorder/details',[OrderDetailController::class,'showforwarehouse']);

Route::post('/accepted',[OrderDetailController::class,'accepted']);

Route::delete('/orderdetail/delete',[OrderDetailController::class,'destroy']);



//.......................add product..........................

Route::resource('/product',ProductController::class);

Route::get('/allproduct',[ProductController::class,'index']);

Route::post('/product/quantity',[ProductController::class,'quantity']);


Route::put('/product',[ProductController::class,'update']);

Route::delete('/product',[ProductController::class,'destroy']);

Route::get('/product',[ProductController::class,'show']);
//.....................order product...........................

Route::post('/orderproduct',[OrderUserDetailController::class,'store']);






//.......................catigore.............................
Route::post('/AddCategory',[CategoryController::class,'store']);

Route::put('/Category',[CategoryController::class,'update']);

Route::delete('/Category',[CategoryController::class,'destroy']);


Route::get('/getmedcineCategory',[CategoryController::class,'show']);

Route::get('/Category/all',[CategoryController::class,'index']);

//........................med pharmacy ..............
Route::post('/get/med',[MedPharmacyController::class,'show']);

Route::post('/search',[MedPharmacyController::class,'search']);

Route::delete('/med_pharmacies',[MedPharmacyController::class,'destroy']);

Route::get('/med_pharmacies/notification',[MedPharmacyController::class,'notification']);

Route::get('/med_pharmacies/exp',[MedPharmacyController::class,'exp']);







Route::post('/med_pharmacies/{id}/deduct_quantity', [MedPharmacyController::class,'deductQuantity']);

//.......................product ..............................

Route::post('/product_quantity/{id}',[ProductController::class,'deductQuantity']);






//..............composition..........................................

Route::post('/add/composition',[CompositionController::class,'store']);


//..................descrption.........................


Route::post('/add/description',[DescrptionController::class,'store']);



Route::get('/get/description',[DescrptionController::class,'show']);


//..................totalprice......................
Route::post('/get/total',[OrderController::class,'totalprice']);




//..........show ph orders...........
Route::get('/view/order',[ProductOrderController::class,'showoforpharmacy']);




//............show ph orders for users..............




Route::post('/pharmacy/order/details',[OrderUserDetailController::class,'accepteforph']);

Route::post('/get/order/detail',[OrderUserDetailController::class,'OrderMedForPharmacy']);

Route::post('/get/order/product/pharmacy',[OrderUserDetailController::class,'OrderproductForPharmacy']);










////////////////////accepte user for orders user//////////////////



////////////////////accepte admin for orders user//////////////////

Route::post('/order/accepte/admin',[ProductOrderController::class,'accepteforadmin']);

///////////////////show orders user for admin///////////////////////

Route::get('/order/user/confirm/admin',[ProductOrderController::class,'Confirmedorders']);

Route::get('/order/user/informed/admin',[ProductOrderController::class,'informedorders']);






Route::post('/view',[MedPharmacyController::class,'view']);

Route::post('/test',[MedicineController::class,'test']);

























