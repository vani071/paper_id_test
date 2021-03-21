<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TransactionController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserDetail']);
    Route::put('/users/{id}', [UserController::class, 'updateUsers']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

    Route::get('/companies', [CompanyController::class, 'getCompanies']);
    Route::get('/companies/{id}', [CompanyController::class, 'getCompanyDetail']);
    Route::post('/companies', [CompanyController::class, 'insertCompany']);
    Route::put('/companies/{id}', [CompanyController::class, 'updateCompanies']);
    Route::delete('/companies/{id}', [CompanyController::class, 'deleteCompany']);

    Route::get('/transactions', [TransactionController::class, 'getTransactions']);
    Route::get('/transactions/{id}', [TransactionController::class, 'getTransactionDetail']);
    Route::put('/transactions/{id}', [TransactionController::class, 'updateTransactions']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'deleteTransaction']);
    Route::post('/transactions', [TransactionController::class, 'insertTransaction']);
});