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

Route::get('/', [ItemController::class, 'index']);
Route::post('/', [ItemController::class, 'store'])->name('item.store');
Route::patch('/{id}', [ItemController::class, 'update'])->name('item.update');
Route::delete('/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
