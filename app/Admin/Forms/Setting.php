<?php

namespace App\Admin\Forms;

use App\Models\Setting as ModelsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Widgets\Form;

class Setting extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Website settings';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        //dump($request->all());

        $switches = ['allow_dinner', 'allow_breakfast', 'allow_lunch'];
        $data = $request->all();
        $data = $this->saveFile($request, 'refer_earn_image', $data);

        // foreach ($switches as $value) {
        //     $data[$value]=$data[$value.'_cb']??$data[$value];
        // }

        ModelsSetting::updateOrCreate(['id' => isAdministrator() ? 1 : Admin::user()->business_id], [
            'json' => $data
        ]);


        admin_success('Processed successfully.');

        // echo json_encode([
        //     'old'=>$request->all(),
        //     'new'=>$data
        // ]);

        return back();
    }
    function saveFile(Request $request, $key, $data)
    {
        if (($request->file($key)) != null) {
            $image = $request->file($key);

            if ($image !== null) {
                $ext = $image->getClientOriginalExtension();
                $dist = "files/images/settings";
                $name = "settings"  . '-' . time() . '.' . $ext;

                $image->move(public_path($dist), $name);

                $data[$key] = $dist . '/' . $name;
            }
        } else {
            if (!isset($data[$key . '_file_del_'])) {
                $oldData = $this->data();
                if (isset($oldData[$key])) {
                    $data[$key] = $oldData[$key];
                }
            }
        }
        return $data;
    }
    /**
     * Build a form here.
     */
    public function form()
    {
        // $this->text('price_per_km', 'Delivery Charge Per KM')->rules('required');
        // $this->image('refer_earn_image', 'Refer & Earn Image');
        // $this->number('refer_earn_new_user_rewards', 'Refere & Earn New user rewards')->default(200)->rules('required');
        // $this->number('refer_earn_old_user_rewards', 'Refere & Earn Old user rewards')->default(0)->rules('required');
        // $this->number('refer_earn_referer_discount', 'Referer user discount (0-100)%')->default(10)->rules('required');
        // $this->number('refer_earn_refered_discount_first_order', 'Refered user discount for 1st order(0-100)%')->default(50)->rules('required');
        // $this->number('refer_earn_refered_discount_second_order', 'Refered user discount for 2nd order(0-100)%')->default(25)->rules('required');
        // $this->number('refer_earn_refered_discount_third_order', 'Refered user discount for 3rd order(0-100)%')->default(10)->rules('required');
        // $this->text('rewardConvertRate', 'Reward convert rate')->rules('required');
       
        if (isAdministrator()) {
            $this->number('invoice_id_label_width', 'invoice_id_label_width')->default(4);
            $this->number('invoice_id_value_width', 'invoice_id_value_width')->default(8);
            $this->number('customer_name_label_width', 'customer_name_label_width')->default(4);
            $this->number('customer_name_value_width', 'customer_name_value_width')->default(8);
            $this->number('item_name_width', 'item_name_width')->default(2);
            
            $this->number('qty_width', 'qty_width')->default(2);
            $this->number('rate_width', 'rate_width')->default(2);
            $this->number('total_width', 'total_width')->default(6);
            $this->number('payment_label_width', 'payment_label_width')->default(6);
            $this->number('payment_value_width', 'payment_value_width')->default(6);
            $this->number('table_label_width', 'table_label_width')->default(6);
            $this->number('table_value_width', 'table_value_width')->default(6);
            $this->number('line_char_len_diff', 'line_char_len_diff')->default(8);
            $this->text('line_char', 'line_char')->default('-');
            $this->text('mid', 'Paytm MID')->rules('required');
            $this->number('is_font_a', 'is_font_a')->default(1);
            $this->url('whatsapp_link', 'Whatsapp Group Link')->rules('required');
            $this->email('email', 'Email Address')->rules('required|email');
            $this->number('show_ads', 'Show Ads in Free Plan')->default(1);
            $this->number('show_int_ads', 'Show Interstitial Ads in Free Plan')->default(1);
            $this->number('service_charge_rate', 'Service charge rate')->default(2);
            $this->number('gst_rate', 'Gst rate')->default(18);
            $this->number('monthly_charge_in_rs', 'Monthly Charge in Rs')->default(100);
            $this->number('monthly_charge_in_doller', 'Monthly Charge in doller')->default(3);
            $this->number('annual_charge_in_rs', 'Annual Charge in Rs')->default(1000);
            $this->number('annual_charge_in_doller', 'Annual Charge in doller')->default(15);
        } else {
            $this->text('shop_name', 'Shop Name')->rules('required');
            $this->text('address', 'Address')->rules('required');
            $this->text('mobile', 'Mobile Number')->rules('required');
            $this->email('email', 'Email Address')->rules('required|email');
            $this->url('map_link', 'Google Map Link')->rules('required');
            $this->text('latitude', 'Shop Location Latitude')->rules('required');
            $this->text('longitude', 'Shop Location  Longitude')->rules('required');

            $states = [
                0 => "No",
                1 => "Yes",
            ];
            $this->text('printer_network_address', 'Printer Network address');
            $this->text('printer_port', 'Printer Port');
            $this->text('footer_message', 'Footer Message')->default("Thank You");
            $this->text('gstin', 'GSTIN');
            $this->select('allow_breakfast', 'Allow Breakfast')->options($states)->default(1);
            $this->select('allow_lunch', 'Allow Lunch')->options($states)->default(1);
            $this->select('allow_dinner', 'Allow Dinner')->options($states)->default(1);
            $this->select('print_gst', 'Print GST')->options($states)->default(1);
            $this->select('is_gst_included', 'Is GST Included')->options($states)->default(1);
            $this->number('gst_rate', 'Enter GST Rate(0-100)%')->default(10)->rules('required');
        }
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $data = ModelsSetting::find(isAdministrator() ? 1 : Admin::user()->business_id);

        if (!$data) {
            ModelsSetting::create([
                'id' => isAdministrator() ? 1 : Admin::user()->business_id
            ]);
        }

        if ($data) {
            return $data->json;
        }

        return [
            'refer_earn_new_user_rewards' => 200,
            'refer_earn_old_user_rewards' => 0,
            'refer_earn_referer_discount' => 10,
            'refer_earn_refered_discount_first_order' => 50,
            'refer_earn_refered_discount_second_order' => 25,
            'refer_earn_refered_discount_third_order' => 10,
            'rewardConvertRate' => "0.25",
            'allow_breakfast' => 1,
            'allow_lunch' => 1,
            'allow_dinner' => 1,
        ];
    }
}
