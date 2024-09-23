<?php

use App\Http\Controllers\HomeController;
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




Route::get('/loginpage',[HomeController::class,'loginpage']);
Route::get('/registerpage',[HomeController::class,'registerpage']);
Route::get('/home', [HomeController::class, 'home'])->middleware('check.session');
Route::get('/addPage', [HomeController::class, 'addPage'])->middleware('check.session');
