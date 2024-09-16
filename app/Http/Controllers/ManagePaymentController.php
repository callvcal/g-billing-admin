<?php

namespace App\Http\Controllers;


use App\Jobs\SendMessages;
use App\Models\AdminUser;
use App\Models\Driver;
use App\Models\EarningModel;
use App\Models\Business;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use paytm\paytmchecksum\PaytmChecksum;
use App\Models\TransactionDetails;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Type;
use Illuminate\Support\Str;

class ManagePaymentController extends Controller

{}