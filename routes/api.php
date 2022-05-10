<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
    //Expense Resource
    Route::apiResources([
        'expense' => ExpenseController::class,//For Expense CRUD
    ]);

    //For Expense Features
    Route::get('/total_amount', 'ExpenseController@totalAmount');
    Route::get('/export_expense' , 'ExpenseController@exportExpense');

    //For Accounting Category Features
    Route::get('/all_accounting_categories', 'AccountingCategoryController@all');



