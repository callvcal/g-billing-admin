<?php

use App\Admin\Controllers\AddressController;
use App\Admin\Controllers\AdsBannerController;
use App\Admin\Controllers\AppHomeController;
use App\Admin\Controllers\BusinessController;
use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\CouponController;
use App\Admin\Controllers\CustomerEventController;
use App\Admin\Controllers\DiningTableController;
use App\Admin\Controllers\DiningTableUserController;
use App\Admin\Controllers\DriverController;
use App\Admin\Controllers\EarningController;
use App\Admin\Controllers\EarningTransactionController;
use App\Admin\Controllers\ExpenseController;
use App\Admin\Controllers\ExpenseTransactionController;
use App\Admin\Controllers\HomeController;
use App\Admin\Controllers\KitchenController;
use App\Admin\Controllers\MaterialController;
use App\Admin\Controllers\MenuController;
use App\Admin\Controllers\OfflineTransactionController;
use App\Admin\Controllers\OrdersController;
use App\Admin\Controllers\OrderStatusUpdateController;
use App\Admin\Controllers\PageDesignerController;
use App\Admin\Controllers\PageDesignerEmbedController;
use App\Admin\Controllers\PageDesignerImagesController;
use App\Admin\Controllers\PageDesignerInlineGalleryController;
use App\Admin\Controllers\PageDesignerTextController;
use App\Admin\Controllers\PageDesignerVideoController;
use App\Admin\Controllers\PizzaKDSController;
use App\Admin\Controllers\POSController;
use App\Admin\Controllers\PushNotificationController;
use App\Admin\Controllers\RawMatrialController;
use App\Admin\Controllers\SellController;
use App\Admin\Controllers\SellItemController;
use App\Admin\Controllers\SpecialDiscountController;
use App\Admin\Controllers\StaffController;
use App\Admin\Controllers\StockController;
use App\Admin\Controllers\SubCategoryController;
use App\Admin\Controllers\TableRequestController;
use App\Admin\Controllers\UnitController;
use App\Admin\Controllers\UserController;
use App\Admin\Controllers\UserCouponController;
use App\Admin\Controllers\WorkingLocationController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SettingController;
use Illuminate\Routing\Router;
use OpenAdmin\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->resource('users', UserController::class);
    $router->resource('addresses', AddressController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('sub-categories', SubCategoryController::class);
    $router->resource('kitchens', KitchenController::class);
    $router->resource('units', UnitController::class);
    $router->resource('menus', MenuController::class);
    $router->resource('sells', SellController::class);
    $router->resource('sell-items', SellItemController::class);
    $router->resource('pos', POSController::class);
    $router->resource('dining-tables', DiningTableController::class);
    $router->resource('table-requests', TableRequestController::class);
    $router->resource('coupons', CouponController::class);
    $router->resource('user-coupons', UserCouponController::class);
    $router->resource('order-status-updates', OrderStatusUpdateController::class);
    $router->resource('kds', PizzaKDSController::class);
    $router->resource('special-discounts', SpecialDiscountController::class);
    $router->resource('push-notifications', PushNotificationController::class);
    $router->resource('dining-table-users', DiningTableUserController::class);
    $router->resource('expenses', ExpenseController::class);
    $router->resource('earning', EarningController::class);
    $router->resource('app-home', AppHomeController::class);
    $router->resource('delivery-boys', DriverController::class);
    $router->resource('ads-banners', AdsBannerController::class);
    $router->resource('customer-events', CustomerEventController::class);
    $router->resource('raw-materials', RawMatrialController::class);
    $router->resource('materials', MaterialController::class);
    $router->resource('businesses', BusinessController::class);
    $router->resource('working-locations', WorkingLocationController::class);
    $router->resource('staffs', StaffController::class);

    $router->get('settings', [SettingController::class, 'create']);
    $router->get('orders', [OrdersController::class, 'orders']);
    $router->post('placeOrder', [POSController::class,'placeOrder']);
    $router->get('php', [HomeController::class, 'php']);
    $router->get('/', 'HomeController@index')->name('home');

    $router->get('print/bill/{id}', [PrintController::class, 'billPrint']);
    $router->get('print/kot/{id}', [PrintController::class, 'kotPrint']);
    $router->get('print/stickers/{id}', [PrintController::class, 'stickersPrint']);
    $router->get('sells-summary/{value}', [HomeController::class, 'getSalesByDateRangeRequest']);
    $router->get('offline-transaction-summary/{value}', [HomeController::class, 'getOfflineTrnByDateRangeRequest']);

    

    
    $router->get('pusher/events', [HomeController::class, function () {
        $user = Admin::user();
        $orderStatus = request()->input('order_status');
        $deliveryStatus = request()->input('delivery_status');
        $message = request()->input('message');
        $title = request()->input('title');

        // Check if the user has the role 'owner'
        $isOwner = $user->isRole('owner');
        $isManager = $user->isRole('manager');

        // if ($orderStatus == 'a_sent') 
        {
            // Trigger the JavaScript function to play audio
            // echo '<script>playAudio("your-audio-file.mp3");</script>';
        }

        if (!$isOwner && !$isManager && !Admin::user()->isAdministrator()) {
            return redirect('admin/kds');
        }

        if ($isManager) {
            return redirect('admin/sells');
        }



        // Return an empty response or JSON response if needed
        return redirect()->back();
    }]);



    $router->get('/load-subcategories', [ControllersHomeController::class, 'loadSubcategories']);






});
