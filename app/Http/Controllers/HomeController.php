<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PremiumPlan;
use App\Models\AdminUser;
use App\Models\AdsBanner;
use App\Models\AppHome;
use App\Models\Business;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Kitchen;
use App\Models\Material;
use App\Models\Menu;
use App\Models\OfflineTransaction;
use App\Models\PremiumPlan as ModelsPremiumPlan;
use App\Models\RawMatrial;
use App\Models\Restorent;
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
        return response($this->query(Menu::class)->latest()->get());
    }
    function banners()
    {
        return response($this->query(AdsBanner::class)->latest()->get());
    }
    function categoryList()
    {
        return response($this->query(Category::class)->latest()->get());
    }
    function subcategories()
    {
        return response($this->query(SubCategory::class)->latest()->get());
    }

    public function ads()
    {

        return $this->query(AdsBanner::class)->with(['subcategory'])->get();
    }
    public function recentProducts()
    {

        return $this->query(Menu::class)->with(['unit'])->where('in_stock', 1)->orderBy("updated_at", "DESC")->limit(10)->get();
    }



    public function home()
    {




        if (isset(apache_request_headers()['isbilling']) && (apache_request_headers()['isbilling'] == 'true')) {
            $admin_id = auth()->user()->id;
            $business_id = auth()->user()->business_id;

            return response([
                'categories' => Category::where('business_id', $business_id)->get(),
                'recentProducts' => Menu::where('business_id', $business_id)->get(),
                'settings' => Setting::find($business_id),
                'app_settings' => Setting::find(1),
                'restorents'=>Restorent::where('business_id', $business_id)->get(),
                'plans'=>ModelsPremiumPlan::all(),
                'sales' => $this->query(Sell::class)->with(['admin.roles','user', 'address', 'items.address'])->whereDate('created_at', today())->where('business_id', $business_id)->get(),
                'tables' => $this->query(DiningTable::class)->with('sell')->where('business_id', $business_id)->get(),
                'units' => (Unit::all()),
                'business' => (Business::find($business_id)),
                'users' => AdminUser::with(['roles','business'])->where('business_id', $business_id)->get(),
                'kitchens' => (Kitchen::where('business_id', $business_id)->get()),
                'materials' => (Material::where('business_id', $business_id)->get()),
                'earning_expense' => (OfflineTransaction::where('business_id', $business_id)->get()),
                'materialsStock' => ($this->query(RawMatrial::class)->with('material')->where('business_id', $business_id)->get()),
                'subCategories' => SubCategory::where('business_id', $business_id)->get(),
            ]);
        }

        return $this->homeUserApp();
    }
    public function homeUserApp()
    {
        $user = User::find(auth()->user()->id);

        // Check if the user does not have a UID or if the UID is more than 4 characters
        if (!isset($user->uid) || strlen($user->uid) > 4) {
            // Create a new UID if necessary
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
            'user' => $user,
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

    public function plans()
    {

        return response([
            'data'=>ModelsPremiumPlan::all()
        ]);
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



    function query($model)
    {
        $user = auth()->user();

        if ($this->isAdministrator($user)) {
            return $model::query();
        }

        return $model::where('business_id', $user->business_id);
    }

    function isAdministrator($user)
    {
        return $this->checkRole($user->id, 'administrator');
    }

    function subQuery($query)
    {
        $user = auth()->user();

        if ($this->isAdministrator($user)) {
            return $query;
        }

        return $query->where('business_id', $user->business_id);
    }
    function checkRole($adminId, $role)
    {
        // Retrieve the user with their roles using Eloquent
        $user = AdminUser::with('roles')->find($adminId);

        // Check if the user exists and has roles
        if (!$user || !$user->roles) {
            return false;
        }

        // Use the 'contains' method to check if any role has the matching slug
        return $user->roles->contains('slug', $role);
    }
}
