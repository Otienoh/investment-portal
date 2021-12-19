<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Modules\Auth\Middleware\CheckKeyRecoveryMiddleware;
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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/auth/github', [AuthController::class, 'redirectToGithub']);
Route::get('/sign-in/github/redirect', [AuthController::class, 'authGitHub']);

Route::get('/', [Controller::class, 'index']);
Route::get('/investment-idea/{id}', [Controller::class, 'index']);
Route::get('/auth', [Controller::class, 'index']);
Route::get('/register', [Controller::class, 'index']);
Route::group(['prefix' => '/'], function () {
    Route::get('/profile/{id}', [Controller::class, 'index'])->where(['id' => '[0-9]+']);
    Route::get('/article/{id}', [Controller::class, 'index'])->where(['id' => '[0-9]+']);
    Route::get('/forgot-password', [Controller::class, 'index']);
    Route::get('/recovery/{key}', [Controller::class, 'index'])->middleware(CheckKeyRecoveryMiddleware::class);
});

Route::group(['prefix' => 'admin-panel', 'middleware' => ['checkRoot']], function () {
    Route::get('/', [Controller::class, 'index']);
    Route::get('/investment-ideas', [Controller::class, 'index']);
    Route::get('/investment-ideas/create-idea', [Controller::class, 'index']);
    Route::get('/smart-analytic', [Controller::class, 'index']);
    Route::get('/articles', [Controller::class, 'index']);
});


