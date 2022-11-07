<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoiceAttatchmentsController;
use App\Http\Controllers\ArchivedInvoicesController;
use App\Http\Controllers\ArchivedInvoiceDetailsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvoicesReportsController;
use Illuminate\Support\Facades\Auth;
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
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function(){ //...

    // Route::get('/', function () {
        //     return view('welcome');
        // });
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('invoices', InvoicesController::class);
        Route::get('/departments/{id}', [InvoicesController::class, 'getProducts']);
        Route::post('/paymentUpdate', [InvoicesController::class, 'paymentUpdate'])->name('invoices.paymentUpdate');
        Route::delete('/forceDelete', [InvoicesController::class, 'forceDelete'])->name('invoices.forceDelete');
        Route::get('/unpaidInvoices', [InvoicesController::class, 'unpaidInvoices'])->name('unpaidInvoices');
        Route::get('/partiallyPaidInvoices', [InvoicesController::class, 'partiallyPaidInvoices'])->name('partiallyPaidInvoices');
        Route::get('/paidInvoices', [InvoicesController::class, 'paidInvoices'])->name('paidInvoices');
        Route::get('/printInvoice/{id}', [InvoicesController::class, 'printInvoice'])->name('printInvoice');

        Route::resource('/archivedInvoices', ArchivedInvoicesController::class);
        Route::get('/unpaidArchivedInvoices', [ArchivedInvoicesController::class, 'unpaidArchivedInvoices'])->name('unpaidArchivedInvoices');
        Route::get('/partiallyPaidArchivedInvoices', [ArchivedInvoicesController::class, 'partiallyPaidArchivedInvoices'])->name('partiallyPaidArchivedInvoices');
        Route::get('/paidArchivedInvoices', [ArchivedInvoicesController::class, 'paidArchivedInvoices'])->name('paidArchivedInvoices');
        Route::get('/printArchivedInvoice/{id}', [ArchivedInvoicesController::class, 'printArchivedInvoice'])->name('printArchivedInvoice');
        Route::patch('/printArchivedInvoice/{id}', [ArchivedInvoicesController::class, 'restoreArchived'])->name('restoreArchived');
        Route::delete('/archivedForceDelete', [ArchivedInvoicesController::class, 'archivedForceDelete'])->name('archivedInvoices.forceDelete');
        Route::post('/archivedPaymentUpdate', [ArchivedInvoicesController::class, 'archivedPaymentUpdate'])->name('archivedPaymentUpdate');

        Route::get('/invoicesReports', [InvoicesReportsController::class, 'index'])->name('invoicesReports');
        Route::post('/searchInvoices', [InvoicesReportsController::class, 'searchInvoices'])->name('searchInvoices');

        Route::get('/customersReports', [CustomersReportsController::class, 'index'])->name('customersReports');
        Route::post('/searchCustomers', [CustomersReportsController::class, 'searchCustomers'])->name('searchCustomers');

        Route::get('/invoiceDetails/{id}', [InvoiceDetailsController::class, 'show'])->name('invoiceDetails-show');
        Route::get('/filePreview/{folderDate}/{invoiceNumber}/{fileName}', [InvoiceDetailsController::class, 'filePreview'])->name('filePreview');
        Route::get('/fileDownload/{folderDate}/{invoiceNumber}/{fileName}', [InvoiceDetailsController::class, 'fileDownload'])->name('fileDownload');
        Route::post('fileDelete', [InvoiceDetailsController::class, 'destroy'])->name('fileDelete');

        Route::get('/archivedInvoiceDetails/{id}', [ArchivedInvoiceDetailsController::class, 'show'])->name('archivedInvoiceDetails-show');
        Route::get('/archivedFilePreview/{folderDate}/{invoiceNumber}/{fileName}', [ArchivedInvoiceDetailsController::class, 'archivedFilePreview'])->name('archivedFilePreview');
        Route::get('/archivedFileDownload/{folderDate}/{invoiceNumber}/{fileName}', [ArchivedInvoiceDetailsController::class, 'archivedFileDownload'])->name('archivedFileDownload');
        //Route::post('archivedFileDelete', [ArchivedInvoiceDetailsController::class, 'destroy'])->name('archivedFileDelete');

        Route::resource('invoiceAttachments', InvoiceAttatchmentsController::class);

        Route::resource('departments', DepartmentsController::class);

        Route::resource('products', ProductsController::class);

        Route::get('export', [InvoicesController::class, 'export'])->name('invoicesExportExcel');
        Route::get('exportPaidInvoices', [InvoicesController::class, 'exportPaidInvoices'])->name('paidInvoicesExportExcel');
        Route::get('exportPartiallyPaidInvoices', [InvoicesController::class, 'exportPartiallyPaidInvoices'])->name('partiallyPaidInvoicesExportExcel');
        Route::get('exportUnPaidInvoices', [InvoicesController::class, 'exportUnPaidInvoices'])->name('UnPaidInvoicesExportExcel');

        Route::get('exportArchived', [ArchivedInvoicesController::class, 'exportArchived'])->name('archivedInvoicesExportExcel');
        Route::get('exportArchivedPaidInvoices', [ArchivedInvoicesController::class, 'exportArchivedPaidInvoices'])->name('archivedPaidInvoicesExportExcel');
        Route::get('exportArchivedPartiallyPaidInvoices', [ArchivedInvoicesController::class, 'exportArchivedPartiallyPaidInvoices'])->name('archivedPartiallyPaidInvoicesExportExcel');
        Route::get('exportArchivedUnPaidInvoices', [ArchivedInvoicesController::class, 'exportArchivedUnPaidInvoices'])->name('archivedUnPaidInvoicesExportExcel');


        Route::group(['middleware' => ['admin']], function () {
            Route::resource('users', \App\Http\Controllers\UserController::class);
            Route::resource('roles', \App\Http\Controllers\RoleController::class);
            Route::resource('permissions', \App\Http\Controllers\PermissionsController::class);

            Route::get('/roles/{role}', [UserController::class, 'getPermissions']);
        });

        Route::get('markOneAsRead/{id}', [InvoicesController::class, 'markOneAsRead'])->name('markOneAsRead');
        Route::get('markOneEditedAsRead/{id}', [InvoicesController::class, 'markOneEditedAsRead'])->name('markOneEditedAsRead');
        Route::get('markOneArchivedAsRead/{id}', [InvoicesController::class, 'markOneArchivedAsRead'])->name('markOneArchivedAsRead');
        Route::get('markOneDeletedAsRead/{id}', [InvoicesController::class, 'markOneDeletedAsRead'])->name('markOneDeletedAsRead');
        Route::get('markAllAsRead', [InvoicesController::class, 'markAllAsRead'])->name('markAllAsRead');


});

