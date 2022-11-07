<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attatchments;
use App\Models\Invoice_details;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivedInvoiceDetailsController extends Controller
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
        $invoiceForBreadcrumbs = Invoice::where('id' , $id)->withTrashed()->first();
        if(str_contains(url()->previous(), '/archivedInvoices') or str_contains(url()->previous(), '/archivedInvoiceDetails/'.$id)) {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.archived_invoices') => false,
                trans('projectlang.archived_invoices_list') => route('archivedInvoices.index'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/unpaidArchivedInvoices') or $invoiceForBreadcrumbs->value_status == '3') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.archived_invoices') => false,
                trans('projectlang.unpaid_archived') => route('unpaidArchivedInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/partiallyPaidArchivedInvoices') or $invoiceForBreadcrumbs->value_status == '2') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.archived_invoices') => false,
                trans('projectlang.partially_paid_archived') => route('partiallyPaidArchivedInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        if(str_contains(url()->previous(), '/paidArchivedInvoices') or $invoiceForBreadcrumbs->value_status == '1') {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => url(app()->getLocale() . '/admin/dashboard'),
                trans('projectlang.archived_invoices') => false,
                trans('projectlang.paid_archived_invoices') => route('paidArchivedInvoices'),
                trans('projectlang.invoice-details') => false,
            ];
        }
        $this->data['lang'] = app()->getLocale();
        $this->data['invoice'] = Invoice::where('id' , $id)->withTrashed()->first();
        $this->data['invoice_details'] = Invoice_details::with('invoice')->where('invoice_id', $id)->first();
        return view('archivedInvoices.archivedInvoice_details', $this->data);
    }

    public function archivedFilePreview($folderDate, $invoiceNumber, $fileName)
    {
        $file = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($folderDate.'/'.$invoiceNumber.'/'.$fileName);
        return response()->file($file);
    }

    public function archivedFileDownload($folderDate, $invoiceNumber, $fileName)
    {
        $file = Storage::disk('uploads')->getDriver()->getAdapter()->applyPathPrefix($folderDate.'/'.$invoiceNumber.'/'.$fileName);
        return response()->download($file);
    }

    /*public function destroy(Request $request)
    {
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
    }*/

}
