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

use OpenAdmin\Admin\Facades\Admin;

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




Admin::js("public/js/pusher.js",);
Admin::js("public/js/print.js",);

Admin::js("js/pusher.js",);
Admin::js("js/print.js",);
