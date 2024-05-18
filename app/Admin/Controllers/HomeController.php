<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\PrepareOrder;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\AdminUser;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\OfflineTransaction;
use App\Models\Sell;
use App\Models\SellItem;
use App\Models\SubCategory;
use App\Models\TableRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Facades\Admin as FacadesAdmin;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Grid\Column as GridColumn;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Widgets\Table;

class HomeController extends AdminController
{




    public function index(Content $content)
    {


        $admins = $this->admins();
        $users_total = 0;
        if (FacadesAdmin::user()->isAdministrator()) {
            $users_total = User::count();
        }

        $activeOrders = $this->query(Sell::class)->whereDate('created_at', now()->toDateString())->whereNotIN('order_status', [
            'g_cancelled',
            'f_rejected'
        ])->count();

        $all = $this->query(Sell::class)->where('order_status', 'e_completed')->groupBy('sell_type')->selectRaw('SUM(total_amt) as total,sell_type as type')->get();
        $today = $this->query(Sell::class)->whereDate('created_at', now()->toDateString())->where('order_status', 'e_completed')->groupBy('sell_type')->selectRaw('SUM(total_amt) as total,sell_type as type')->get();


        $startDate = Carbon::now()->subHours(24);
        $endDate = Carbon::now();

        $sells = $this->getSalesByDateRange($startDate, $endDate);


        $transactions = $this->getOfflineTrnByDateRange($startDate, $endDate);

        $data = [
            'counts' => [
                [
                    'name' => "Dining Requests",
                    'count' => $this->query(TableRequest::class)->count(),
                ],
                [
                    'name' => "Admins",
                    'count' => $admins,
                ],


                [
                    'name' => "Categories",
                    'count' => $this->query(Category::class)->count(),
                ],
                [
                    'name' => "Subcategories",
                    'count' => $this->query(SubCategory::class)->count(),
                ],
                [
                    'name' => "Today's Orders",
                    'count' => $this->query(Sell::class)->whereDate('created_at', now()->toDateString())->count(),
                ],

                [
                    'name' => "Total food item",
                    'count' => $this->query(Menu::class)->count(),
                ],
                [
                    'name' => "Total available food item",
                    'count' => $this->query(Menu::class)->where('in_stock', 1)->count(),
                ],
                [
                    'name' => "Active Orders",
                    'count' => $this->query(Sell::class)->whereDate('created_at', now()->toDateString())->whereIN('order_status', [
                        'a_sent',
                        'b_accepted',
                        'c_preparing',
                        'd_readyToPickup',
                    ])->count(),
                ],
                [
                    'name' => "Completed Orders",
                    'count' => $this->query(Sell::class)->whereDate('created_at', now()->toDateString())->whereIN('order_status', [

                        'e_completed',
                    ])->count(),
                ],
            ],

            'statistics' => [
                'sells' => $sells
            ],
            'offlineTranctions' => $transactions

        ];



        $total = 0;
        foreach ($all as $item) {
            $total = $total + $item->total;
            array_unshift($data['counts'], [
                'name' => "Total " . ucfirst($item->type),
                'count' => "Rs." . $item->total,
            ]);
        }
        array_unshift($data['counts'], [
            'name' => "Total Sale",
            'count' => "Rs." . $total,
        ]);
        $total = 0;
        foreach ($today as $item) {
            $total = $total + $item->total;
            array_unshift($data['counts'], [
                'name' => "Today " . ucfirst($item->type),
                'count' => "Rs." . $item->total,
            ]);
        }
        array_unshift($data['counts'], [
            'name' => "Today Sale",
            'count' => "Rs." . $total,
        ]);
        if ($users_total > 0) {
            array_unshift($data['counts'], [
                'name' => "Customers",
                'count' => $users_total,
            ],);
        }




        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('Dashboard')
            ->description('Summary...')
            ->body(view('index', compact('data')));
    }


    function getSalesByDateRange($startDate, $endDate)
    {

        if ($startDate != null) {
            $sells = $this->subQuery(DB::table('sells')
                ->select(DB::raw('DATE(date_time) as date'), DB::raw('SUM(total_amt) as total_sales'))
                ->where('order_status', 'e_completed')
                ->whereBetween('date_time', [$startDate, $endDate]))
                ->groupBy('date')
                ->get();
        } else {
            $sells = $this->subQuery(DB::table('sells')
                ->select(DB::raw('DATE(date_time) as date'), DB::raw('SUM(total_amt) as total_sales'))
                ->where('order_status', 'e_completed'))
                ->groupBy('date')
                ->get();
        }



        return $sells;
    }
    function getOfflineTrnByDateRange($startDate, $endDate)
    {

        if ($startDate != null) {
            $trns = $this->query(OfflineTransaction::class)->whereBetween('created_at', [$startDate, $endDate])->selectRaw('SUM(amount) as amount, type')
                ->groupBy('type')
                ->get();
        } else {
            $trns = $this->query(OfflineTransaction::class)->selectRaw('SUM(amount) as amount, type')
                ->groupBy('type')
                ->get();
        }



        return $trns;
    }
    public function getSalesByDateRangeRequest($value)
    {

        $startDate = Carbon::now();
        $endDate = Carbon::now();
        switch ($value) {
            case 'day':
                $startDate = Carbon::now()->subHours(24);
                break;

            case 'week':
                $startDate = Carbon::now()->subWeek(1);
                break;

            case 'month':
                $startDate = Carbon::now()->subMonth(1);
                break;

            case '6month':
                $startDate = Carbon::now()->subMonth(6);
                break;

            case 'all':
                $startDate = null;
                break;
        }
        $sells = $this->getSalesByDateRange($startDate, $endDate);
        $trns = $this->getOfflineTrnByDateRange($startDate, $endDate);
        return response()->json([
            'sells' => $sells,
            'trns' => $trns,
        ]);
    }



    public function pusher()
    {
        $user = FacadesAdmin::user();

        // Check if the user has the role 'owner'
        $isOwner = $user->isRole('owner');

        return response()->json(['is_owner' => $isOwner]);
    }


    function query($model)
    {
        $user = FacadesAdmin::user();

        if ($user->isAdministrator()) {
            return $model::query();
        }

        return $model::where('business_id', $user->business_id);
    }
    function subQuery($query)
    {
        $user = FacadesAdmin::user();

        if ($user->isAdministrator()) {
            return $query;
        }

        return $query->where('business_id', $user->business_id);
    }
    function admins()
    {
        if (FacadesAdmin::user()->isAdministrator()) {
            return AdminUser::count();
        }

        return AdminUser::where('business_id', FacadesAdmin::user()->business_id)->count();
    }
}
