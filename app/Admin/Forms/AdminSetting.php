<?php

namespace App\Admin\Forms;

use App\Models\Setting as ModelsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Widgets\Form;

class AdminSetting extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Admin settings';

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

        ModelsSetting::updateOrCreate(['id' => 1], [
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

        $states = [
            0 => "No",
            1 => "Yes",
        ]; {

            $this->number('invoice_id_label_width', 'invoice_id_label_width')->default(4);
            $this->number('invoice_id_value_width', 'invoice_id_value_width')->default(8);
            $this->number('customer_name_label_width', 'customer_name_label_width')->default(4);
            $this->number('customer_name_value_width', 'customer_name_value_width')->default(8);
            $this->number('item_name_width', 'item_name_width')->default(2);
            $this->number('image_quality', 'image_quality')->default(15);
            $this->number('qty_width', 'qty_width')->default(2);
            $this->number('rate_width', 'rate_width')->default(2);
            $this->number('total_width', 'total_width')->default(6);
            $this->number('payment_label_width', 'payment_label_width')->default(6);
            $this->number('payment_value_width', 'payment_value_width')->default(6);
            $this->number('table_label_width', 'table_label_width')->default(6);
            $this->number('table_value_width', 'table_value_width')->default(6);
            $this->number('line_char_len_diff', 'line_char_len_diff')->default(8);
            $this->number('empty_lines_after_kot', 'empty_lines_after_kot')->default(1);
            $this->text('line_char', 'line_char')->default('-');
            $this->number('is_font_a', 'is_font_a')->default(1);
            
            $this->select('print_new_line', 'print_new_line')->options($states)->default(1);
            $this->select('reset_bluetooth', 'reset_bluetooth')->options($states)->default(1);
            $this->select('show_line_above_total', 'show_line_above_total')->options($states)->default(1);
            $this->select('show_line_below_total', 'show_line_below_total')->options($states)->default(1);
            $this->select('is_font_a_all', 'is_font_a_all')->options($states)->default(1);
            $this->select('bill_disable_hf', 'bill_disable_hf')->options($states)->default(1);
        }
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $data = ModelsSetting::find(1);

        if (!$data) {
            ModelsSetting::create([
                'id' => 1
            ]);
        }

        if ($data) {
            $json = $data->json;
            $json['id'] = $data->id;
            return $json;
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