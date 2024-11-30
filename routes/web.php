<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\FoodProductController;
use App\Http\Controllers\PageDesignerController;
use App\Http\Controllers\ProfileController;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/refresh', function (Request $request) {
    $signature = 'sha256=' . hash_hmac('sha256', $request->getContent(), "GSSSYzBqcO6JvyH8kiI2Zsco0VmkuFwb8J0MVawQCbAehkNUvjsMwq6gaBDLuep");

    if (!hash_equals($request->header('x-hub-signature-256'), $signature)) {
        $message = "Signatures didn't match";
        return;
    }
    exec('git pull origin main ', $output);
    echo json_encode($output);
    return response(['message' => "success"]);
});

Route::get('/force', function (Request $request) {
    exec('/usr/bin/git pull origin eatplan8_new 2>&1', $output);
    echo json_encode($output);
    return response(['message' => "success"]);
});
Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('body.about');
});

Route::get('/contact', function () {
    return view('body.contact');
});
Route::get('/purchase', function () {
    return redirect('/dashboard');
})->name('app');

Route::get('/checkout', function () {
    return view('body.checkout');
});


// Route::get('/pusher/events', function () {


//     return json_encode(Admin::user());
// });

Route::get('/page/{id}', [PageDesignerController::class, 'index'])->name('page-designer');


Route::get('/shop', [FoodProductController::class, 'index'])->name('shop.index');

Route::get('/account-delete-request', function () {
    return view('account-delete-request');
});

Route::get('/download-src', function () {
    $filePath = public_path('geatinsta.zip');

    // Check if the file exists
    if (File::exists($filePath)) {
        // Set headers for file download
        $headers = [
            'Content-Type' => 'application/zip',
        ];

        // Return the file as a response
        return Response::download($filePath, 'geatinsta.zip', $headers);
    } else {
        // If file not found, return error response
        return Response::make('File not found.', 404);
    }
});

Route::get('/account-delete-request', function () {
    return view('account-delete-request');
});

Route::get('/app-ads.txt', function () {
    return file('app-ads.txt');
});
Route::get('/about', function () {
    return view('about');
});


Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});


Route::get('/refunds-policy', function () {
    return view('refunds-policy');
});


require __DIR__ . '/auth.php';


Route::get('/software-download', function () {
    return view('software-download');
});


Route::get('/dev', function () {
    foreach (Menu::all() as $item) {
        echo "$item->id : " . (new BarcodeController())->genBarcode($item);
    }
});



