<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('welcome');
// });



// p


Route::resource('user', UserController::class);
Route::resource('role', RoleController::class);


Route::prefix('api')->name('api.')->group(function () {
    Route::post('user', [UserController::class, 'api'])->name('user');
    Route::post('role', [RoleController::class, 'api'])->name('role');
});
