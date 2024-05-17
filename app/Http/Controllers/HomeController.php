<?php

namespace App\Http\Controllers;

use App\Models\AdsBanner;
use App\Models\AppHome;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Kitchen;
use App\Models\Material;
use App\Models\Menu;
use App\Models\RawMatrial;
use App\Models\Sell;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\TableRequest;
use App\Models\UID;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    function products()
    {
        return response(Menu::latest()->get());
    }
    function banners()
    {
        return response(AdsBanner::latest()->get());
    }
    function categoryList()
    {
        return response(Category::latest()->get());
    }
    function subcategories()
    {
        return response(SubCategory::latest()->get());
    }

    public function ads()
    {

        return AdsBanner::with(['subcategory'])->get();
    }
    public function recentProducts()
    {

        return Menu::with(['unit'])->where('in_stock', 1)->orderBy("updated_at", "DESC")->limit(10)->get();
    }

    

    public function home()
    {
        $user = User::find(auth()->user()->id);
        if (!isset($user->uid)) {
            $user->uid = UID::create([
                'user_id' => $user->id
            ])->uid;
            $user->save();
        }

        if (isset(apache_request_headers()['isdriver']) && (apache_request_headers()['isdriver'] == 'true')) {

            return response([
                'settings' => Setting::find(1),
                'sales' => Sell::with(['address', 'user'])->where('driver_id', auth()->user()->id)->get(),
            ]);
        }
        if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {

            return response([
                'categories' => $this->categories(),
                'recentProducts' => Menu::all(),
                'settings' => Setting::find(1),
                'sales' => Sell::with(['user','address','items.address'])->whereDate('created_at', today())->get(),
                'tables' => DiningTable::with('sell')->get(),
                'units' => (Unit::all()),
                'kitchens' => (Kitchen::all()),
                'materials' => (Material::all()),
                'materialsStock' => (RawMatrial::with('material')->get()),
                'subCategories' => SubCategory::all(),
            ]);
        }
        return response([
            'ads' => $this->ads(),
            'categories' => $this->categories(),
            'recentProducts' => Menu::with('unit')->where('in_stock', 1)->get(),
            'home' => AppHome::with('menus.unit')->get(),
            'serviceLocations' => WorkingLocation::all(),
            'settings' => Setting::find(1),
            'account' => (new WalletController())->getAccBal(),
            'sales' => (new OrderController())->getSales(),
            'carts' => (new OrderController())->getCarts(),
            'dining_table_requests' => TableRequest::with('diningTable')->where('user_id', auth()->user()->id)->get(),
            'addresses' => (new OrderController())->getAddress(),
            'subCategories' => SubCategory::with(['category'])->get(),

        ]);
    }

    public function categories()
    {

        return (Category::all());
    }

    public function getServiceLocation()
    {

        $locations = WorkingLocation::all();

        return response($locations);
    }
    public function checkServiceLocation(Request $request)
    {
        $pincode = $request->pincode;

        $location = WorkingLocation::where('pincode', $pincode)->first();

        if (!$location) {
            return response([
                'message' => "Sorry, currenty we are not providing service at this pincode, try again after some time."
            ], 401);
        }
        return response($location);
    }

    function menuItemsBySubCategory($id)
    {
        return response(Menu::with(['unit'])->where('subcategory_id', $id)->where('in_stock', 1)->get());
    }

    function subCategoriesByCategory($id)
    {
        return response(SubCategory::with(['category'])->where('category_id', $id)->get());
    }

    public function loadSubcategories(Request $request)
    {
        $provinceId = $request->get('query');
        return SubCategory::where('category_id', $provinceId)->get(['id', DB::raw('name as text')]);
    }
}
