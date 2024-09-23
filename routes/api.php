<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[HomeController::class,'register']);
Route::post('/login', [HomeController::class, 'login']);
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');


Route::post('/getdata', [HomeController::class, 'getdata']);
Route::post('/adddata', [HomeController::class, 'adddata']);
Route::post('/updatedata', [HomeController::class, 'updatedata']);
Route::post('/deletedata', [HomeController::class, 'deletedata']);

