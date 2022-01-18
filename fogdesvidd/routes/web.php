<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


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
Route::get('home',[ItemController::class, 'index']);
Route::get('fetch-item',[ItemController::class, 'fetchitem']);
Route::post('/home',[ItemController::class, 'store']);
Route::get('edit-item/{id}',[ItemController::class, 'edit']);
Route::put('update-item/{id}',[ItemController::class, 'update']);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'adminHome'])->name('admin.home')
->middleware('is_admin');
