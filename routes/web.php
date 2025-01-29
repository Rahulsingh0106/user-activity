<?php

use App\Http\Controllers\UserActivityController;
use App\Models\UserActivity;
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


Route::get('/', [UserActivityController::class, 'index'])->name('/');
Route::post('/recalculate-ranks', [UserActivityController::class, 'recalculateRanks'])->name('recalculate-ranks');
