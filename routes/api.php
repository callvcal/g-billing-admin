<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CouponApi;
use App\Http\Controllers\DiningTableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RazorPayController;
use App\Http\Controllers\ReferalController;
use App\Http\Controllers\ReferalTransactionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorySubcategoryItemController;
use App\Http\Controllers\InAppPurchaseController;
use App\Http\Controllers\ManagePaymentController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SettingController;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Kitchen;
use App\Models\Menu;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;

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

Route::post('/auth/users/verifyOTP', [AuthController::class, 'verifyOTP'])->middleware('security');
Route::get('/app/home', [HomeController::class, 'home']);

Route::post('/auth/users/verifyOTP', [AuthController::class, 'verifyOTP'])->middleware('security');
Route::post('/auth/users/otp/email', [AuthController::class, 'sendUserEmailOtp'])->middleware('security');
Route::post('/auth/users/otp/mobile', [AuthController::class, 'sendUserMobileOtp'])->middleware('security');
Route::post('/auth/users/otp/verify/email', [AuthController::class, 'verifyUserEmailOtp'])->middleware('security');
Route::post('/auth/custom/signup', [AuthController::class, 'signUpCustom'])->middleware('security');
Route::post('/auth/custom/signin', [AuthController::class, 'signInCustom'])->middleware('security');
Route::post('/auth/check/email', [AuthController::class, 'isEmailExists'])->middleware('security');
Route::post('/auth/users/platform-sign-in-check', [AuthController::class, 'verifyGAF'])->middleware('security');
Route::post('/auth/business/platform-sign-in-check', [BusinessController::class, 'verifyGAF'])->middleware('security');
Route::get('/auth/forgot/password/{email}', [AuthController::class, 'sendResetLink']);
Route::post('/auth/reset/password', [AuthController::class, 'resetPassword']);

Route::post('/auth/admin/login', [AuthController::class, 'adminLogin'])->middleware('security');


Route::post('paytm/callback', [ManagePaymentController::class, 'onCallback']);

///public apis

Route::get('/products', [HomeController::class, 'products']);
Route::get('/categories', [HomeController::class, 'categories']);
Route::get('/subcategories', [HomeController::class, 'subcategories']);
Route::get('/banners', [HomeController::class, 'banners']);

Route::post('/load-subcategories', [HomeController::class, 'loadSubcategories']);


Route::group(['middleware' => ['security', 'auth:sanctum']], function () {

    Route::get('/auth/test', [AuthController::class, 'test']);
    Route::get('/categories', [HomeController::class, 'categories']);

    Route::post('/check/service-location', [HomeController::class, 'checkServiceLocation']);
    Route::post('/users/dp/update', [AuthController::class, 'saveDp']);
    Route::post('/decodeLatLng', [LocationController::class, 'decodeLatLng']);
    Route::get('/users/dp/delete', [AuthController::class, 'deleteDp']);
    Route::post('/users/provider/data', [AuthController::class, 'updateProviderData']);
    Route::post('/cart/add-remove', [OrderController::class, 'toggleCart']);
    Route::post('/user/update/fcm', [AuthController::class, 'saveFCM']);
    Route::post('/addresses/update', [OrderController::class, 'updateAddress']);
    Route::get('/addresses/all', [OrderController::class, 'getAddress']);
    Route::get('/addresses/delete/{id}', [OrderController::class, 'deleteAddress']);

    Route::get('/home', [HomeController::class, 'home']);
    Route::post('/settings/update', [SettingController::class, 'update']);

    Route::get('/sales/items/{id}', [OrderController::class, 'getOrderItems']);
    Route::post('/sales/place', [OrderController::class, 'placeOrder']);
    Route::post('/auth/users/save/mobile', [AuthController::class, 'saveMobile']);

    //-----------------------Location-API-------------------------------
    Route::post('/location/search', [LocationController::class, 'search']);
    Route::post('/location/decodeLatLng', [LocationController::class, 'decodeLatLng']);



    //-----------------------COUPONS-API-------------------------------
    Route::get('/coupons', [CouponApi::class, 'index']);
    Route::get('/coupons/validate', [CouponApi::class, 'validateCoupon']);


    Route::post('/dining-table/request', [DiningTableController::class, 'requestTable']);
    Route::post('/dining-table/pay', [DiningTableController::class, 'pay']);
    Route::get('/dining-table/get/{id}', [DiningTableController::class, 'get']);
    Route::post('/dining-table/payment/success', [RazorPayController::class, 'paymentSuccessDining']);
    Route::post('/dining-table/payment/failed', [RazorPayController::class, 'paymentErrorDining']);
    Route::get('/dining-table/cancel/{id}', [DiningTableController::class, 'cancelTable']);
    Route::post('/auth/provider/data', [AuthController::class, 'updateProviderData']);
    Route::post('/user/data/update', [AuthController::class, 'updateProviderData']);
    Route::get('/category/sub-categories/{id}', [HomeController::class, 'subCategoriesByCategory']);
    Route::get('/sub-category/menu-items/{id}', [HomeController::class, 'menuItemsBySubCategory']);


    Route::post('/payment/success', [RazorPayController::class, 'paymentSuccess']);
    Route::post('/payment/error', [RazorPayController::class, 'paymentError']);

    Route::post('/payment/wallet/success', [RazorPayController::class, 'paymentSuccessWallet']);
    Route::post('/payment/wallet/error', [RazorPayController::class, 'paymentErrorWallet']);
    Route::get('/wallet/acc', [WalletController::class, 'getAccBal']);
    Route::post('/wallet/credit', [WalletTransactionController::class, 'initCredit']);
    Route::post('/wallet/convert', [WalletTransactionController::class, 'rewardConvert']);
    Route::post('/refer/test', [ReferalTransactionController::class, 'create']);



    //-----------------------REFER-AND-EARN API------------------------------
    Route::get('/refer/summary/{code}', [ReferalController::class, 'summary']);

    Route::get('/orders/cancel/{id}', [OrderController::class, 'cancelOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'getSaleAt']);
    Route::get('/all/orders', [OrderController::class, 'getAllSales']);
    Route::get('/refer/my-discounts', [CouponApi::class, 'specialDiscount']);
    Route::get('/orders/delivery/update/{sellId}', [OrderController::class, 'updateDeliveryStatus']);


    Route::get('/check-auth', [AuthController::class, 'test']);
    Route::get('/order/timeline/{orderId}', [OrderController::class, 'orderTimeLine']);
    Route::post('/carts/bulk/update', [OrderController::class, 'bulkCartsUpdate']);




    ///admin-apis
    Route::get('/users/staffs/fetch', [StaffController::class, 'all']);
    Route::post('/users/staffs/create', [StaffController::class, 'create']);
    Route::get('/users/staffs/delete/{id}', [StaffController::class, 'delete']);

    Route::get('/categories', [CategorySubcategoryItemController::class, 'categories']);
    Route::get('/subcategories', [CategorySubcategoryItemController::class, 'subCategories']);
    Route::get('/menus', [CategorySubcategoryItemController::class, 'menus']);
    Route::get('/raw-materials', [CategorySubcategoryItemController::class, 'rawMaterials']);
    Route::get('/raw-materials-stock', [CategorySubcategoryItemController::class, 'rawMaterialStocks']);
    Route::get('/earning-expense', [CategorySubcategoryItemController::class, 'earningExpense']);

    Route::post('/create-category', [CategorySubcategoryItemController::class, 'createCategory']);
    Route::post('/create-kitchen', [CategorySubcategoryItemController::class, 'createKitchen']);
    Route::post('/create-subcategory', [CategorySubcategoryItemController::class, 'createSubCategory']);
    Route::post('/create-menu', [CategorySubcategoryItemController::class, 'createMenu']);
    Route::post('/create-unit', [CategorySubcategoryItemController::class, 'createUnit']);
    Route::post('/create-din-table', [CategorySubcategoryItemController::class, 'createDinTable']);
    Route::post('/raw-materials/create', [CategorySubcategoryItemController::class, 'createRawMaterial']);
    Route::post('/raw-materials-stock/create', [CategorySubcategoryItemController::class, 'createRawMaterialStock']);
    Route::post('/earning-expense/create', [CategorySubcategoryItemController::class, 'createEarningExpense']);

    Route::delete('/delete-category/{id}', [CategorySubcategoryItemController::class, 'deleteCategory']);
    Route::delete('/delete-kitchen/{id}', [CategorySubcategoryItemController::class, 'deleteKitchen']);
    Route::delete('/delete-subcategory/{id}', [CategorySubcategoryItemController::class, 'deleteSubCategory']);
    Route::delete('/delete-menu/{id}', [CategorySubcategoryItemController::class, 'deleteMenu']);
    Route::delete('/delete-unit/{id}', [CategorySubcategoryItemController::class, 'deleteUnit']);
    Route::delete('/delete-din-table/{id}', [CategorySubcategoryItemController::class, 'deleteDinTable']);
    Route::delete('/delete-raw-materials/{id}', [CategorySubcategoryItemController::class, 'deleteRawMaterial']);
    Route::delete('/delete-raw-materials-stock/{id}', [CategorySubcategoryItemController::class, 'deleteRawMaterialStock']);
    Route::delete('/earning-expense/{id}', [CategorySubcategoryItemController::class, 'deleteEarningExpense']);

    Route::post('/billing/place', [OrderController::class, 'placeOrderPOS']);


    Route::post('/business/set', [BusinessController::class, 'setBusiness']);
    Route::get('/business/verify', [BusinessController::class, 'verifyBusiness']);

    Route::post('/business/pay', [ManagePaymentController::class, 'buyPlan']);
    Route::post('/business/payment/success', [ManagePaymentController::class, 'paymentSuccess']);


    Route::post('/purchase/valid', [InAppPurchaseController::class, 'validPurchase']);
    Route::post('/purchase/invalid', [InAppPurchaseController::class, 'invalidPurchase']);
    Route::get('/product/stock/update/{menuId}', [CategorySubcategoryItemController::class, 'updateStock']);

    Route::get('/eatinsta/data/default', [CategorySubcategoryItemController::class, 'importDefaultData']);
    Route::get('/menu/stock/fetch/{menuId}', [CategorySubcategoryItemController::class, 'stocks']);
    Route::post('/menu/stock/adjust', [CategorySubcategoryItemController::class, 'adjustStock']);
    Route::post('/staffs/password/change', [AuthController::class, 'changePassword']);



    ///Recipe Part
    Route::post('/recipe/create', [RecipeController::class, 'createRecipe']);
    Route::get('/recipes', [RecipeController::class, 'recipes']);
    Route::delete('/recipe/delete/{id}', [RecipeController::class, 'deleteRecipe']);
    Route::post('/recipe/material/create', [RecipeController::class, 'addMaterial']);
    Route::delete('/recipe/material/delete/{id}', [RecipeController::class, 'deleteRecipeMaterial']);
});


Route::get('/eatplan8-import', function () {

    $response = Http::get('https://eatplan8.com/callvcal-export');
    $data = $response->json();

    $adminId = 172;
    $businessId = 137;

    // Helper function to map old IDs to new IDs
    function mapOldToNewId($oldId, $idArray) {
        return collect($idArray)->firstWhere('old_id', $oldId)['new_id'] ?? null;
    }

    // Process Units
    $unitId = [];
    foreach ($data['units'] as $unit) {
        array_push($unitId, [
            'old_id' => $unit['id'],
            'new_id' => Unit::updateOrCreate(
                ['name' => $unit['name']],
                [
                    'name' => $unit['name'],
                    'business_id' => $businessId,
                    'admin_id' => $adminId,
                ]
            )->id,
        ]);
    }

    // Process Kitchens
    $kitchenId = [];
    foreach ($data['kitchen'] as $kitchen) {
        array_push($kitchenId, [
            'old_id' => $kitchen['id'],
            'new_id' => Kitchen::create([
                'name' => $kitchen['name'],
                'business_id' => $businessId,
                'admin_id' => $adminId,
            ])->id,
        ]);
    }

    // Process Dining Tables
    $tablesId = [];
    foreach ($data['tables'] as $table) {
        array_push($tablesId, [
            'old_id' => $table['id'],
            'new_id' => DiningTable::create([
                'name' => $table['name'],
                'capacity' => $table['capacity'] ?? 1,
                'number' => $table['number'] ?? 1,
                'business_id' => $businessId,
                'admin_id' => $adminId,
            ])->id,
        ]);
    }

    // Process Categories
    $categoriesId = [];
    foreach ($data['categories'] as $category) {
        array_push($categoriesId, [
            'old_id' => $category['id'],
            'new_id' => Category::create([
                'name' => $category['name'],
                'business_id' => $businessId,
                'admin_id' => $adminId,
            ])->id,
        ]);
    }

    // Process Subcategories
    $subCategoriesId = [];
    foreach ($data['sub_categories'] as $subcategory) {
        array_push($subCategoriesId, [
            'old_id' => $subcategory['id'],
            'new_id' => SubCategory::create([
                'name' => $subcategory['name'],
                'business_id' => $businessId,
                'admin_id' => $adminId,
                'category_id' => mapOldToNewId($subcategory['category_id'], $categoriesId),
                'kitchen_id' => mapOldToNewId($subcategory['kitchen_id'], $kitchenId),
            ])->id,
        ]);
    }

    // Process Products
    $products = [];

    foreach ($data['products'] as $product) {
        array_push($products, [
            'old_id' => $product['id'],
            'new_id' => Menu::create([
                'name' => $product['name'],
                'business_id' => $businessId,
                'admin_id' => $adminId,
                'category_id' => mapOldToNewId($product['category_id'], $categoriesId),
                'subcategory_id' => mapOldToNewId($product['subcategory_id'], $subCategoriesId),
                'unit_id' => mapOldToNewId($product['unit_id'], $unitId),
                'price' => $product['price'] ?? 0,
                'description' => $product['description'] ?? null,
                'allow_delivery' => $product['allow_delivery'] ?? 0,
                'allow_dine_in' => $product['allow_dine_in'] ?? 0,
                'allow_take_away' => $product['allow_take_away'] ?? 0,
                'image' => $product['image'] ?? null,
                'subtitle' => $product['subtitle'] ?? null,
                'code' => $product['code'] ?? null,
                'in_stock' => $product['in_stock'] ?? 1,
                'discount' => $product['discount'] ?? 0,
                'food_type' => $product['food_type'] ?? null,
                'ratings' => $product['ratings'] ?? 0,
                'sells' => $product['sells'] ?? 0,
                'active' => $product['active'] ?? 1,
                'stock_status' => $product['stock_status'] ?? null,
                'calories_count' => $product['calories_count'] ?? null,
                'weight_per_serving' => $product['weight_per_serving'] ?? null,
                'proteins_count' => $product['proteins_count'] ?? null,
                'qty' => $product['qty'] ?? 1,
            ])->id,
        ]);
    }
    

    return response()->json([
        'units' => $unitId,
        'kitchens' => $kitchenId,
        'tables' => $tablesId,
        'categories' => $categoriesId,
        'sub_categories' => $subCategoriesId,
        'products' => $products,
    ]);
});
