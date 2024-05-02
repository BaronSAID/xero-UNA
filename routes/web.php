<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\XeroController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login','login');
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::get('/logout',[LoginController::class, 'logout'])->name('logout');

/*
 * We name this route xero.auth.success as by default the config looks for a route with this name to redirect back to
 * after authentication has succeeded. The name of this route can be changed in the config file.
 */
Route::get('/manage/xero', [XeroController::class, 'index'])->name('xero.auth.success');
Route::get('/getItems', [XeroController::class, 'getItems'])->name('getItems');
Route::get('/getContacts', [XeroController::class, 'getContacts'])->name('getContacts');
