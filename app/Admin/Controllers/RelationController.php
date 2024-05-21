<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\BillPrint;
use App\Admin\Actions\UserGender;
use App\Http\Controllers\Controller;
use OpenAdmin\Admin\Facades\Admin;

class RelationController
{

    public function gridUser($grid)
    {


        return $grid->user()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
    }


    public function gridMenu($grid)
    {


        return $grid->menu()->display(function ($model) {
            if ($model == null) {
                return  "N/A";
            }
            return $model['name'];
        })->sortable();
    }
    public function gridSell($grid)
    {


        return $grid->sell()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['cooking_notes'] . '</span> ';
        })->sortable();
    }

    public function gridTable($grid)
    {


        return  $grid->diningTable()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
    }
    public function gridDriver($grid)
    {


        return  $grid->driver()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "Not assigned" . '</span> ';
            }

            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
    }
    public function detailsUser($show)
    {
        return   $show->user('User information', function ($model) {

            $model->setResource('/admin/users');

            $model->id();
            $model->name();
            $model->email();
            $model->mobile();
        });
    }
    public function detailsTable($show)
    {
        return   $show->diningTable('Dinning Table information', function ($model) {

            $model->setResource('/admin/dining-tables');

            $model->id();
            $model->name();
            $model->number();
            $model->capacity();
        });
    }
    public function detailsDriver($show)
    {
        return  $show->driver('Delivery Boy information', function ($model) {

            $model->setResource('/admin/drivers');

            $model->id();
            $model->name();
            $model->email();
            $model->mobile();
        });
    }
    public function detailsAddress($show)
    {
        return  $show->address('Address information', function ($model) {

            $model->setResource('/admin/addresses');

            $model->id();
            $model->name();
            $model->city();
            $model->district();
            $model->plot_no();
            $model->pincode();
            $model->state();
            $model->address();
        });
    }
    public function detailsSellItems($show)
    {
        return   $show->items('Items', function ($items) {

            $items->setResource('/admin/sell-items');
            $items->menu()->image()->image('', 64, 64);
            $items->menu()->name();
            $items->total_amt();
            $items->qty();
            $items->discount_amt();
            $items->gst_amt();
        });
    }



    public function gridActions($grid, $type = null)
    {
        if (!(isAdministrator() || (Admin::user()->isRole('owner')))) {

            if (Admin::user()->isRole('manager')) {
                $grid->actions(function ($actions) {
                    $actions->add(new BillPrint());
                    $actions->disableDelete();
                });
            } else {
                $grid->actions(function ($actions) {

                    $actions->add(new BillPrint());
                    $actions->disableDelete();
                    $actions->disableEdit();
                });
            }
        } else {
            $grid->actions(function ($actions) {

                $actions->add(new BillPrint());
            });

            if ($type == 'sells') {
                $this->gridUser($grid);
                $this->gridDriver($grid);
            }
        }
    }
    public function showTools($show)
    {
       
        if (!(isAdministrator() || (Admin::user()->isRole('owner')))) {

            if (Admin::user()->isRole('manager')) {
               
                
                $show->panel()
                ->tools(function ($tools) {
                    $tools->disableDelete();
                });
            } else {
                
                $show->panel()
                ->tools(function ($tools) {
                    $tools->disableDelete();
                    $tools->disableEdit();
                });
            }
        } 
        
        
    }


    public function gridSubCategory($grid)
    {


        return    $grid->subcategory()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
    }
    public function detailsSubCategory($show)
    {
        return    $show->user('Subcategory information', function ($model) {

            $model->setResource('/admin/subcategories');

            $model->id();
            $model->name();
            $model->email();
            $model->mobile();
        });
    }
}
