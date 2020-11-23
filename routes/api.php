<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// NEW FOR USER CREDS
Route::group([
    'middleware' => 'api',
    'prefix' => 'users'

], function ($router) {
    Route::post('signup', [UserController::class, 'signup']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('current-user-profile', [UserController::class, 'getAuthenticatedUser'])->middleware('jwt.verify');
    Route::get('all', [UserController::class, 'index'])->middleware('jwt.verify');
    Route::get('one/{userId}', [UserController::class, 'findOne'])->middleware('jwt.verify');
    Route::post('edit-profile/{userId}', [UserController::class, 'updateProfile'])->middleware('jwt.verify');
    Route::post('remove-user/{userId}', [UserController::class, 'removeUser'])->middleware('jwt.verify');
    // Route::post('logout', 'AuthController@logout');
    // Route::post('refresh', 'AuthController@refresh');
    // Route::get('user-profile', 'AuthController@userProfile');
});