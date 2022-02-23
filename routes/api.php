<?php

use Illuminate\Http\Request;

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



Route::post('/loanAppFirstStep', 'MainController@firstStep');

Route::prefix('/')->middleware(['LoanAuthCheck'])->group(function () {

    Route::post('/loanAppSecondStep', 'MainController@secondStep');
    Route::post('/loanAppRepaymentStep', 'MainController@processEmi');
    Route::get('/loanAppViewRepaymentStep', 'MainController@repayamentSchedule');
});
