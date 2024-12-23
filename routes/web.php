<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserApplicationsController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\UserMyDetailsController;
use App\Http\Controllers\WelcomeController;
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

Route::resource('/', WelcomeController::class);

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/logout', function () {
    session()->flush();
    return redirect("/");
});

Route::resource("/login", LoginController::class);
Route::resource("/user_home", UserHomeController::class);
Route::resource("/user_details", UserMyDetailsController::class);
Route::resource("/user_applications", UserApplicationsController::class);
