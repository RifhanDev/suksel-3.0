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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fpx_queue', 'TransactionsController@queue_fpx_requery')->name('fpx_queue');
Route::post('api_fpx_requery', 'TransactionsController@api_fpx_requery')->name('api_fpx_requery');

Route::prefix('v1')->group(function () {
    Route::post('vendor', 'App\Http\Controllers\API\ApiController@vendorApi')->name('vendorApi');
    Route::post('tender', 'App\Http\Controllers\API\ApiController@tenderApi')->name('tenderApi');
    Route::post('transaction', 'App\Http\Controllers\API\ApiController@transactionApi')->name('transactionApi');
    Route::post('tender_agency', 'App\Http\Controllers\API\ApiController@tenderAgency')->name('tenderAgencyApi');       //10-FEB-2025  API utk tender agency AUFA & MBSA
    Route::post('detail_vendor', 'App\Http\Controllers\API\ApiController@detailVendor')->name('detailVendorApi');        //10-FEB-2025  API utk maklumat vendor AUFA & MBSA
    Route::post('transaction_contract', 'App\Http\Controllers\API\ApiController@transactionContract')->name('transactionContractApi'); //10-FEB-2025  API utk transaksi tender agency AUFA & MBSA
});

Route::get('/mail-manager/test', 'MailManagerController@test');
Route::get('/mail-manager/unsend-today-email', 'MailManagerController@resend_unsend_daily_email');
Route::get('/mail-manager/unsend-weekly-email', 'MailManagerController@resend_unsend_this_week_email');
Route::get('/mail-manager/unsend-monthly-email', 'MailManagerController@resend_unsend_this_month_email');
Route::get('/decrypt/{string}', 'MailManagerController@decrypt');
Route::get('/encrypt/{id}', 'MailManagerController@encrypt');
Route::get('/test-email', 'MailManagerController@test_email');
Route::post('regen-tender-eligible', 'TendersController@regen_tender_eligible');
