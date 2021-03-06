<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ToDoItemController;


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

Route::get('', WelcomeController::class);

Auth::routes();
Auth::routes(['verify' => true]);

Route::resource('task', ToDoItemController::class)->middleware('auth');

Route::put('task/update-done/{task}', [ToDoItemController::class, 'updateDone'])->name('task.updateDone')->middleware('auth');
Route::post('task/shared/{task}', [ToDoItemController::class, 'shared'])->name('task.shared')->middleware('auth');
