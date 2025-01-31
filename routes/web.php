<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LPBlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LPContactController;
use App\Http\Controllers\LPPricingController;
use App\Http\Controllers\LPFeaturesController;
use App\Http\Controllers\BusinessFileController;
use App\Http\Controllers\LandingPagesController;
use App\Http\Controllers\LPBlogSingleController;
use App\Http\Controllers\API\MidtransAPIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    ("Storage Link Success");
});

Route::get('/', function () {
    return view('landing_pages/index');
});

//Midtrans Relation
Route::get('midtrans/success', [MidtransAPIController::class, 'success']);
Route::get('midtrans/unfinish', [MidtransAPIController::class, 'unfinish']);
Route::get('midtrans/error', [MidtransAPIController::class, 'error']);


// Route::resource('masterProductCategories', App\Http\Controllers\MasterProductCategoryController::class);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('frontend.dashboard');
});

// route group with sanctum middleware
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', function () {
        return view('frontend.dashboard');
    });
});

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template');

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate');

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback');

Route::post(
    'generator_builder/generate-from-file',
    '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
)->name('io_generator_builder_generate_from_file');


// route get dashboard index
Route::get('/', [LandingPagesController::class, 'index'])->name('home');
Route::get('/features', [LPFeaturesController::class, 'index'])->name('features');
Route::get('/pricing', [LPPricingController::class, 'index'])->name('pricing');
Route::get('/blog', [LPBlogController::class, 'index'])->name('blog');
Route::get('/blog-single', [LPBlogSingleController::class, 'index'])->name('blog-single');
Route::get('/contact', [LPContactController::class, 'index'])->name('contact');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/data', [DashboardController::class, 'FrekuensiTransaksi'])->name('dashboard');

Route::post('/businessFile/index', [BusinessFileController::class, 'store'])->name('business_files.store');

Route::post('/business/index', [BusinessController::class, 'store'])->name('businesses.store');
Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

Route::get('/products/create_service', [ProductController::class, 'create_service'])->name('products.create_service');




Route::resource('masterProductCategories', App\Http\Controllers\MasterProductCategoryController::class);


Route::resource('masterBusinessCategories', App\Http\Controllers\MasterBusinessCategoryController::class);


Route::resource('masterStatusBusinesses', App\Http\Controllers\MasterStatusBusinessController::class);


Route::resource('masterUnits', App\Http\Controllers\MasterUnitController::class);


Route::resource('masterPrivileges', App\Http\Controllers\MasterPrivilegeController::class);


Route::resource('masterProvinces', App\Http\Controllers\MasterProvinceController::class);


Route::resource('masterDeliveryServices', App\Http\Controllers\MasterDeliveryServiceController::class);


Route::resource('masterPaymentMethods', App\Http\Controllers\MasterPaymentMethodController::class);


Route::resource('masterTransactionCategories', App\Http\Controllers\MasterTransactionCategoryController::class);


Route::resource('masterTransactionCategories', App\Http\Controllers\MasterTransactionCategoryController::class);


Route::resource('masterStatusUsers', App\Http\Controllers\MasterStatusUserController::class);


Route::resource('users', App\Http\Controllers\UserController::class);


Route::resource('businesses', App\Http\Controllers\BusinessController::class);


Route::resource('businessFiles', App\Http\Controllers\BusinessFileController::class);


Route::resource('businessCategories', App\Http\Controllers\BusinessCategoryController::class);


Route::resource('master_cities', App\Http\Controllers\MasterCityController::class);


Route::resource('subDistricts', App\Http\Controllers\SubDistrictController::class);


Route::resource('addresses', App\Http\Controllers\AddressController::class);


Route::resource('products', App\Http\Controllers\ProductController::class);


Route::resource('productCategories', App\Http\Controllers\ProductCategoryController::class);


Route::resource('openHours', App\Http\Controllers\OpenHourController::class);


Route::resource('businessDeliveryServices', App\Http\Controllers\BusinessDeliveryServiceController::class);


Route::resource('shippingCostVariables', App\Http\Controllers\ShippingCostVariableController::class);


Route::resource('shippingUseds', App\Http\Controllers\ShippingUsedController::class);


Route::resource('discounts', App\Http\Controllers\DiscountController::class);


Route::resource('salesDeliveryServices', App\Http\Controllers\SalesDeliveryServiceController::class);


Route::resource('productFiles', App\Http\Controllers\ProductFileController::class);


Route::resource('businessPaymentMethods', App\Http\Controllers\BusinessPaymentMethodController::class);


Route::resource('salesTransactions', App\Http\Controllers\SalesTransactionController::class);


Route::resource('ratings', App\Http\Controllers\RatingController::class);


Route::resource('transactionStatuses', App\Http\Controllers\TransactionStatusController::class);


Route::resource('transactionProducts', App\Http\Controllers\TransactionProductController::class);


Route::resource('balances', App\Http\Controllers\BalancesController::class);


Route::resource('events', App\Http\Controllers\EventController::class);


Route::resource('news', App\Http\Controllers\NewsController::class);


Route::resource('announcements', App\Http\Controllers\AnnouncementController::class);


Route::resource('eventRegisters', App\Http\Controllers\EventRegisterController::class);


Route::resource('productDiscounts', App\Http\Controllers\ProductDiscountController::class);


Route::resource('masterBanks', App\Http\Controllers\MasterBankController::class);


Route::resource('withdrawBalances', App\Http\Controllers\WithdrawBalanceController::class);


