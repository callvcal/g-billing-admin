<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\PushNotification;
use OpenAdmin\Admin\Facades\Admin;

class PushNotificationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PushNotification';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PushNotification());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('',64,64);
        // $grid->column('topic', __('topic'));
        $grid->column('title', __('title'));
        $grid->column('body', __('Body'));
        // $grid->column('date_time', __('Date time'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(PushNotification::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('title'));
        $show->field('body', __('Body'));
        $show->field('image', __('Image'));
        $show->field('date_time', __('Date time'));
        $show->field('admin_id', __('Admin id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PushNotification());

        $form->text('title', __('title'))->required();
        // $form->text('topic', __('Notification topic'))->default('offer')->required();
        $form->textarea('body', __('Body'))->required();
        $form->image('image', __('Image'));
        // $form->datetime('date_time', __('Date time'))->default(date('Y-m-d H:i:s'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        
        return $form;
    }
}
