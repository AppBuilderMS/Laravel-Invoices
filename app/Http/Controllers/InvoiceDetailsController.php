<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attatchments;
use App\Models\Invoice_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use function PHPUnit\Framework\isEmpty;

class InvoiceDetailsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['title'] = trans('projectlang.invoice-details'); // set the page title
        $this->data['heading'] = trans('projectlang.invoice');
        $invoiceForBreadcrumbs = Invoice::where('id' , $id)->first();
        if(str_contains(url()->previous(), '/invoices') or str_contains(url()->previous(), '/invoiceDetails/'.$id)) {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin')     => url('/'),
                trans('projectlang.invoices') => false,
                trans('projectlang.invoices-list') => route('invoices.index'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/unpaidInvoices') or $invoiceForBreadcrumbs->value_status == '3') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.non-paid') => route('unpaidInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/partiallyPaidInvoices') or $invoiceForBreadcrumbs->value_status == '2') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.partialy-paid') => route('partiallyPaidInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/paidInvoices') or $invoiceForBreadcrumbs->value_status == '1') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.paid-invoices') => route('paidInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        $this->data['lang'] = app()->getLocale();
        $this->data['invoice'] = Invoice::where('id' , $id)->first();
        $this->data['invoice_details'] = Invoice_details::with('invoice')->where('invoice_id', $id)->first();
        return view('invoices.invoice_details', $this->data);
    }

    public function filePreview($folderDate, $invoiceNumber, $fileName)
    {
        $file = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($folderDate.'/'.$invoiceNumber.'/'.$fileName);
        return response()->file($file);
    }

    public function fileDownload($folderDate, $invoiceNumber, $fileName)
    {
        $file = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($folderDate.'/'.$invoiceNumber.'/'.$fileName);
        return response()->download($file);
    }

    public function destroy(Request $request)
    {
//        dd($request->attach_id);
        $attachment = Invoice_attatchments::findOrFail($request->attach_id);
        $attachment->delete();
        Storage::disk('uploads')->delete($request->folder_date.'/'.$request->invoice_number.'/'.$request->file_name);

        $FileSystem = new Filesystem();
        $invoiceNumberFolder = Storage::disk('uploads')->path($request->folder_date.'/'.$request->invoice_number);
        $dateFolder = Storage::disk('uploads')->path($request->folder_date);

        //Check if the invoiceNumberFolder exists.
        if ($FileSystem->exists($invoiceNumberFolder)) {
            // Get all files in this invoiceNumberFolder.
            $files = $FileSystem->files($invoiceNumberFolder);
            // Check if invoiceNumberFolder is empty.
            if (empty($files)) {
                // Yes, delete the invoiceNumberFolder.
                $FileSystem->deleteDirectory($invoiceNumberFolder);
            }
        }

        //Check if the dateFolder exists.
        if ($FileSystem->exists($dateFolder )) {
            // Get all folders in this dateFolder.
            $folders = $FileSystem->directories($dateFolder);
            // Check if dateFolder is empty.
            if (empty($folders)) {
                // Yes, delete the dateFolder.
                $FileSystem->deleteDirectory($dateFolder);
            }
        }

        session()->flash('success', trans('projectlang.attach_deleted_success'));
        return back();
    }

}
