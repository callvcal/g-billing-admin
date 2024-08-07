<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\PrepareOrder;
use App\Admin\Forms\Setting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KotTokenController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RazorPayController;
use App\Models\Address;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\Sell;
use App\Models\SellItem;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
use OpenAdmin\Admin\Widgets\Box;
use OpenAdmin\Admin\Widgets\Table;
use Illuminate\Support\Str;

class POSController extends AdminController
{

    protected $colors = [
        'running' => ['orange', 'white'],
        'blank' => ['white', 'black'],
        null => ['white', 'black'],
        'printed' => ['blue', 'white'],
        'paid' => ['green', 'white'],
        'kot' => ['indigo', 'white']
    ];




    public function index(Content $content)
    {


        $subcategories = $this->query(SubCategory::class)->where('menus','>',0)->get();

        // return json_encode([
        //     'sql'=>$this->query(Menu::class)->where('price', ">", 0)->toSql(),
        //     'data'=>$this->query(Menu::class)->where('price', ">", 0)->get(),
        // ]);
        $menus = $this->query(Menu::class)->where('price', ">", 0)->get();

        $tables = $this->query(DiningTable::class)->get();



        $sell = new Sell();
        $items = [];
        $diningID = request()->query('dining_table_id');
        if (isset($diningID)) {
            $table = $this->query(DiningTable::class)->find($diningID);
            if ($table) {
                $sell = $this->query(Sell::class)->where('uuid', $table->sell_id)->first();
                if ($sell) {
                    $items = $this->query(SellItem::class)->with('menu')->where('sell_id', $sell->uuid)->get();
                } else {
                    $sell = new Sell();
                }
            }
        }

        $running = [];

        $view = view("widgets.subcategories", compact(['subcategories', 'menus', 'tables', 'running', 'sell', 'items']));
        return $content
            ->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
            ->title('POS Table Booking')
            ->body($view);
        // ->row(function (Row $row) use ($subcategories, $menus, $tables, $running, $sell, $items) {
        //     $row->column(12, function (Column $column) use ($subcategories, $menus, $tables, $running, $sell, $items) {
        //         $column->append(view("widgets.subcategories", compact(['subcategories', 'menus', 'tables', 'running', 'sell', 'items'])));
        //     });
        // });
    }
    public function placeOrder(Request $request)
    {
        $orderData = $request->toArray();
        $user = FacadesAdmin::user();
        $orderData['business_id'] = $user->business_id;
        $orderData['admin_id'] = $user->id;

        $orderData['date_time'] = Carbon::now();
        $orderData['user_id'] = null;
        $orderData['uuid'] = $orderData['uuid'] ?? Str::orderedUuid();
        $orderData['delivery_status'] = "a_unassigned";
        $orderData['payment_status'] = $request->payment_status;
        $orderData['order_status'] = ($request->payment_status == 'paid') ? "e_completed" : 'unknown';
        if ($request->pos_action == 'BILL') {
            $orderData['order_status'] = 'e_completed';
        }

        $setting = (new Setting())->data();

        $orderData['invoice_id'] = (new KotTokenController())->generateToken(type: 'invoice');

        $gst = 0;

        $gstRate = 0;
        $orderData['gst_amt'] = 0;

        if ((isset($setting['print_gst']))&&(isset($setting['gst_rate']))&&($setting['print_gst'] === 1)) {
            $gstRate = $setting['gst_rate'];
            $isIncluded = $setting['is_gst_included'] == 1;

            if ($isIncluded) {
                $gst =(int) $this->calculateGSTIncluded($request->total_amt, $gstRate);
            } else {
                $gst =(int) $this->calculateGSTExcluded($request->total_amt, $gstRate);
                $orderData['total_amt'] =  $request->total_amt + $gst;
            }
            $orderData['gst_amt'] = $gst;
        }


       
        $orderData['order_id'] = (new OrderController())->generateOrderID(1);

        if ($orderData['payment_status'] == 'paid') {
            $orderData['due_amt'] =  0;
            $orderData['paid_amt'] =   $orderData['total_amt'];
        }

        $order = Sell::updateOrCreate(
            ['id' => $request->id],
            $orderData
        );
        Log::channel('callvcal')->info("Order:business_id: " . $order->business_id);


        $items = [];

        $itemsJson = json_decode($request->items);
        foreach ($itemsJson as $key => $item) {

            $orderItemData = [
                'date_time' =>  Carbon::now(),
                'qty' => $item->qty,
                'total_amt' => $item->total_amt,
                'user_id' => null,
                'business_id' => $order->business_id,
                'token_number' => (new KotTokenController())->generateToken(),
                'menu_id' => $item->menu_id,
                'sell_id' => $order->uuid,
            ];
            $model = SellItem::updateOrCreate(
                ['id' => $item->id ?? null],
                $orderItemData
            );
            $model->load('menu');
            array_push($items, $model);
        }

        // return json_encode($request->toArray());
        if (isset($order->dining_table_id)) {
            $table = $this->query(DiningTable::class)->find($order->dining_table_id);
            $table->customer_name = $order->customer_name;
            $table->customer_mobile = $order->customer_mobile;
            $table->amount = $order->total_amt;
            $table->sell_id = $order->uuid;
            if ($request->pos_action == 'BILL') {
                $table->customer_name = null;
                $table->customer_mobile = null;
                $table->amount = null;
                $table->sell_id = null;
                $table->status = 'blank';
            } else {
                $table->customer_name = $order->customer_name;
                $table->customer_mobile = $order->customer_mobile;
                $table->amount = $order->total_amt;
                $table->sell_id = $order->uuid;
                $table->status = 'running';
            }

            $order->pos_status = $request->pos_action;
            $order->save();
            $table->save();
        }

        if ($request->pos_action == 'KOT') {
            $data = [
                "sell" => $order,
                'items' => $items,
                'setting' => $setting,
                'height' => (count($items) * 50)
            ];
            $view = view('templates.kot', $data)->render();
            return response()->json(['html' => $view]);

            return redirect("/admin/print/kot/" . $order->id);
        }
        if ($request->pos_action == 'BILL') {
            $order->load(['user', 'diningTable']);
            $image = (new RazorPayController())->getQRcode($order);
            $data = [
                "sell" => $order,
                'items' => $items,
                'image' => $image,
                'setting' => $setting,
                'height' => 172 + (count($items) * 12)
            ];
            $view = view('templates.bill', $data)->render();
            return response()->json(['html' => $view]);
            return redirect("/admin/print/bill/" . $order->id);
        } else {
            return redirect('/admin/pos');
        }
    }
    function query($model)
    {
        $user = FacadesAdmin::user();

        if (isAdministrator()) {
            return $model::query();
        }

        return $model::where('business_id', $user->business_id);
    }
    function subQuery($query)
    {
        $user = FacadesAdmin::user();

        if (isAdministrator()) {
            return $query;
        }

        return $query->where('business_id', $user->business_id);
    }
    function calculateGSTIncluded($total, $gstRate)
    {
        $gst = $total / (1 + ($gstRate / 100));

        return ($total-$gst);
    }
    function calculateGSTExcluded($total, $gstRate)
    {
        $gst = $total * ($gstRate / 100);
        return $gst;
    }
}
