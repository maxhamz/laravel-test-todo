<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

// Route::get('/tasks/all',[TasksController::class, 'index'])->name('dashboard');
Route::get('/tasks/all',[TaskController::class, 'index']);

//ADDED
Route::get('/tasks/one/{taskId}',[TaskController::class, 'findOne']);

// Route::get('/task',[TaskController::class, 'add']);
Route::post('/tasks/create',[TaskController::class, 'createNew']);

// 
// Route::get('/task/{task}', [TaskController::class, 'edit']);
// Route::post('/task/{task}', [TaskController::class, 'update']);

// added
// Route::put('/tasks/complete/{taskId}', [TaskController::class, 'updateTaskStatus']);
// Route::put('/tasks/update/{taskId}', [TaskController::class, 'updateTaskDesc']);
// Route::delete('/tasks/drop/{taskId}', [TaskController::class, 'dropTask']);
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

// // USER ROUTES
// Route::get('/users/all', [UserController::class, 'index']);
// Route::get('/users/one/{userId}', [UserController::class, 'findOne']);
Route::post('/users/signup', [UserController::class, 'signup']);
Route::post('/users/login', [UserController::class, 'login']);
Route::put('/users/{userId}/edit-profile', [UserController::class, 'updateProfile']);
Route::post('/users/{userId}/remove', [UserController::class, 'removeUser']);