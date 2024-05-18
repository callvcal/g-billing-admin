<?php

/**
 * Open-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * OpenAdmin\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * OpenAdmin\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Illuminate\Support\Facades\Log;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

OpenAdmin\Admin\Form::forget(['editor']);
Admin::css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
// Admin::css('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
Admin::js('https://js.pusher.com/8.2.0/pusher.min.js');
// Admin::js('vendor/chartjs/dist/chart.js');
// Admin::js("https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js");
// Admin::js("https://code.jquery.com/jquery-3.6.0.min.js");
// Admin::js("https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js");


Admin::html('<audio id="ringtone" src="https://eatplan8.callvcal.com/ringtones/ring.mp3" hidden ></audio>');
Admin::html('<div class="toast-container position-fixed bottom-0 end-0 p-3">
<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <strong id="time">11 mins ago</strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" id="close"></button>
  </div>
  <div class="toast-body" id="message">
    Hello, world! This is a toast message.
  </div>
</div>
</div>');




// Admin::js("public/js/pusher.js",);
// Admin::js("public/js/print.js",);

Admin::js("js/pusher.js",);
Admin::js("js/print.js",);
Grid::init(function (Grid $grid) {

  $grid->enableDblClick();

  $grid->disableActions();

  if (!isAdministrator()) {
    $grid->model()->where('business_id', Admin::user()->business_id)->orderBy('id', 'DESC');
  } else {
    $grid->orderBy('id', 'DESC');
  }


  // $grid->disablePagination();


  // $grid->disableFilter();

  // $grid->disableRowSelector();

  // $grid->disableColumnSelector();

  // $grid->disableTools();

  // $grid->disableExport();

  if (!canAllowCreate()) {
    $grid->disableCreateButton();
  }

  $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {

    if (!canAllowEdit()) {
      $actions->disableEdit();
    }
    if (!canAllowDelete()) {
      $actions->disableDelete();
    }
  });
  
  $grid->tools(function (Grid\Tools $tools) {
    $tools->batch(function (Grid\Tools\BatchActions $actions) {
      if (!canAllowDelete()) {
        $actions->disableDelete();
      }
      if (!canAllowEdit()) {
        $actions->disableEdit();
      }
    });
  });

  if(isAdministrator()){
    $grid->column('business_id', __('Business ID'));

  }


});


Show::init(function (Show $show) {
  $show->panel()
    ->tools(function ($tools) {

      if (!canAllowEdit()) {
        $tools->disableEdit();
      }
      if (!canAllowDelete()) {
        $tools->disableDelete();
      }
    });;
    if(isAdministrator()){
      $show->field('business_id', __('Business ID'));
  
    }
});


Form::init(function (Form $form) {




  $form->tools(function (Form\Tools $tools) {

    if (!canAllowView()) {
      $tools->disableView();
    }
    if (!canAllowDelete()) {
      $tools->disableDelete();
    }
  });
});


/*
Roles
1) Adminstrator (For Company Only)
-----> Can Access/Edit/Delete All Business Data

2) Partner-Admin (Business Admin)
-----> Can Access/Edit/Delete Only Own Business Data

3) Partner-Manager* (Business Manager)
-----> Can Access/Edit Only Own Business Data


4) Partner-* (Business Other Staff)

*/





function isAdministrator()
{
  return Admin::user()->isAdministrator();
}
function is($role)
{
  return Admin::user()->isRole($role);
}

function canAllowDelete()
{
  if (isAdministrator()) {
    return true;
  }
  if (is('Partner-Admin')) {
    return true;
  }
  return false;
}


function canAllowCreate()
{
  if (isAdministrator()) {
    return true;
  }
  if (is('Partner-Admin')) {
    return true;
  }
  if (is('Partner-Manager')) {
    return true;
  }

  return false;
}


function canAllowEdit()
{
  if (isAdministrator()) {
    return true;
  }
  if (is('Partner-Admin')) {
    return true;
  }
  if (is('Partner-Manager')) {
    return true;
  }
  return false;
}


function canAllowView()
{
  if (isAdministrator()) {
    return true;
  }
  if (is('Partner-Admin')) {
    return true;
  }
  if (is('Partner-Manager')) {
    return true;
  }
  return false;
}
