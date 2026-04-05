<?php

use App\Http\Middleware\CheckLogin;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SjController;

Auth::routes();
Route::group(['middleware' => [CheckLogin::class]], function () {
	Route::get('/', [DashboardController::class, 'index'])->name('home');
});

Route::controller(DashboardController::class)->name('dashboard.')->group(function () {
	Route::get('/dashboard', 'dashboard')->name('index');
	Route::get('/sj/dashboard', 'sj_dashboard')->name('sj.index');
	Route::post('/filter_view', 'filter_view')->name('filter');
	Route::get('/sj_outstanding', 'sj_outstanding')->name('outstanding');
	Route::get('/sj_outstanding_finance', 'sj_outstanding_finance')->name('outstanding.finance');
	Route::post('/data_sj', 'data_sj')->name('data.sj');
	Route::post('/data_outstanding_sj', 'data_outstanding_sj')->name('data.outstanding');
	Route::post('/data_outstanding_sj_7_day', 'data_outstanding_sj_7_day')->name('data.outstanding7');
	Route::post('/data_outstanding_sj_7_day_finance', 'data_outstanding_sj_7_day_finance')->name('data.outstanding7finance');
	Route::get('/sj_error', 'sj_error')->name('error.index');
	Route::post('/data_sj_error', 'data_sj_error')->name('error.data');
});

Route::controller(SjController::class)->name('sj.')->group(function () {
	Route::get('/upload/sj/dashboard', 'upload_sj_dashboard')->name('upload.index');
	Route::post('/upload/sj/dashboard', 'upload_sj_dashboard_store')->name('upload.store');
	Route::get('/sj_balik', 'sj_balik')->name('balik.index');
	Route::post('/sj_balik', 'sj_balik_store')->name('balik.store');
	Route::post('/update_sj_balik_ppic_upload', 'update_sj_balik_ppic_upload')->name('balik.upload');
	Route::get('/terima_finance', 'terima_finance')->name('finance.index');
	Route::post('/terima_finance', 'terima_finance_store')->name('finance.store');
	Route::post('/update_fin_upload', 'update_fin_upload')->name('finance.upload');
	Route::get('/delete_sj/{id}', 'del_ppic')->name('delete');
	Route::get('/edit_sj/{id}', 'sj_update')->name('edit');
	Route::post('/edit_sj/{id}', 'sj_update_store')->name('update');
	Route::get('/create/sj', 'create_sj')->name('create');
	Route::post('/create/sj', 'create_sj_store')->name('store');
	Route::get('/download_sj', 'download_sj')->name('download');
});

Route::controller(CustomerController::class)->name('customer.')->group(function () {
	Route::post('/customer', 'customer_store')->name('store');
	Route::get('/customer', 'customer')->name('index');
	Route::get('/customer/create', 'customer_create')->name('create');
	Route::post('/customer/store-form', 'customer_store_form')->name('store.form');
	Route::get('/edit_customer/{id}', 'customer_edit')->name('edit');
	Route::post('/edit_customer/{id}', 'customer_update')->name('update');
	Route::get('/delete_customer/{id}', 'customer_delete')->name('delete');
});

Route::controller(InvoiceController::class)->name('invoice.')->group(function () {
	Route::post('/invoice', 'update_invoice_upload')->name('upload');
	Route::get('/invoice', 'invoice')->name('index');
	Route::get('/invoicing', 'invoicing')->name('invoicing');
	Route::post('/data_invoice', 'data_invoice')->name('data');
});
