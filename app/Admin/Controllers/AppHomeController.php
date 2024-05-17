<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\AppHome;
use App\Models\Artist;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Music;
use App\Models\MusicCategory;
use App\Models\MusicPlaylist;
use OpenAdmin\Admin\Facades\Admin;

class AppHomeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */

    protected $title = 'Home Page UI';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AppHome());
        (new RelationController())->gridActions($grid);


        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'))->sortable();
        $grid->column('menus', __('Items'))->display(function ($array) {
            return (isset($array) ? count($array) : 0) . " Items";
        })->label()->sortable();


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
        $show = new Show(AppHome::findOrFail($id));

        $show->field('title', __('Title'));
        $show->menus('Items', function ($songs) {
            $songs->resource('/admin/menus');

            $songs->id();
            $songs->name();
            $songs->image()->image("", 64, 64);



            $songs->filter(function ($filter) {
                $filter->like('name');
            });
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AppHome());

        $form->text('title', __('Title (referance)'));

        $form->multipleSelect('menus', 'Select items')->options(Menu::all()->pluck('name', 'id'));
        $form->hidden('admin_id', __('Admin'))->defaultOnEmpty(Admin::user()->id);


        return $form;
    }
}
