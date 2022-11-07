<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Invoice_details;
use App\Models\Product;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArchivedInvoicesExport;
use App\Exports\ArchivedPaidInvoicesExport;
use App\Exports\ArchivedPartiallyPaidInvoicesExport;
use App\Exports\ArchivedUnPaidInvoicesExport;

class ArchivedInvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.archived_invoices'); // set the page title
        $this->data['heading'] = trans('projectlang.archived_invoices_list');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.archived_invoices') => false,
            trans('projectlang.archived_invoices_list') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::onlyTrashed()->with('department', 'product')->get();
        return view('archivedInvoices.allArchivedInvoices', $this->data);
    }

    /**
     * Display the specified resource.
     */
    public function unpaidArchivedInvoices()
    {
        $this->data['title'] = trans('projectlang.unpaid_archived'); // set the page title
        $this->data['heading'] = trans('projectlang.unpaid_archived');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.archived_invoices') => false,
            trans('projectlang.unpaid_archived') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::onlyTrashed()->with('department', 'product')->where('value_status', 3)->get();
        return view('archivedInvoices.unpaidArchivedInvoices', $this->data);
    }

    /**
     * Display the specified resource.
     */
    public function partiallyPaidArchivedInvoices()
    {
        $this->data['title'] = trans('projectlang.partially_paid_archived'); // set the page title
        $this->data['heading'] = trans('projectlang.partially_paid_archived');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.archived_invoices') => false,
            trans('projectlang.partially_paid_archived') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::onlyTrashed()->with('department', 'product')->where('value_status', 2)->get();
        return view('archivedInvoices.partiallyPaidArchivedInvoices', $this->data);
    }

    /**
     * Display the specified resource.
     */
    public function paidArchivedInvoices()
    {
        $this->data['title'] = trans('projectlang.paid_archived_invoices'); // set the page title
        $this->data['heading'] = trans('projectlang.paid-invoices');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.archived_invoices') => false,
            trans('projectlang.paid_archived_invoices') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::onlyTrashed()->with('department', 'product')->where('value_status', 1)->get();
        return view('archivedInvoices.paidArchivedInvoices', $this->data);
    }

    public function printArchivedInvoice($id)
    {
        $this->data['title'] = trans('projectlang.print_preview'); // set the page title
        $this->data['heading'] = trans('projectlang.print_preview');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.invoices') => false,
            trans('projectlang.invoices-list') => route("invoices.index"),
            trans('projectlang.print_preview') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoice'] = Invoice::where('id', $id)->with('department', 'product')->withTrashed()->first();
        $this->data['departments'] = Department::all();
        $this->data['products'] = Product::all();
        return view('invoices.printPreview', $this->data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = trans('projectlang.invoice_edit'); // set the page title
        $this->data['heading'] = trans('projectlang.invoice_edit');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.invoices') => false,
            trans('projectlang.invoices-list') => route("invoices.index"),
            trans('projectlang.invoice_edit') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoice'] = Invoice::where('id', $id)->with('department', 'product')->withTrashed()->first();
        $this->data['departments'] = Department::all();
        $this->data['products'] = Product::all();
        return view('archivedInvoices.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoice = Invoice::withTrashed()->where('id', $request->id)->with('department', 'product')->first();
        $invoice->update([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_date,
            'due_date'          => $request->due_date,
            'department_id'     => $request->department_id,
            'product_id'        => $request->product_id,
            'collection_amount' => $request->collection_amount,
            'commission_rate'   => $request->commission_rate,
            'commission_amount' => $request->commission_amount,
            'discount_rate'     => $request->discount_rate,
            'discount'          => $request->discount,
            'sub_total'         => $request->sub_total,
            'rate_vat'          => $request->rate_vat,
            'value_vat'         => $request->value_vat,
            'total'             => $request->total,
            'notes_ar'          => $request->notes_ar,
            'notes_en'          => $request->notes_en
        ]);
        session()->flash('success', trans('projectlang.invoice_edited_successfully'));
        return redirect()->route('archivedInvoices.index');
    }

    public function archivedPaymentUpdate(Request $request)
    {
        $invoice = Invoice::where('id' , $request->invoice_id)->withTrashed()->first();
        $invoice_details = Invoice_details::where('invoice_id', $request->invoice_id)->first();
        if($request->status == '2')
        {
            $invoice->update([
                'value_status' =>  '2',
                'status_ar' => 'مدفوعة جزئيا',
                'status_en' => 'partially paid',
                'partial_payment_date' => $request->partial_payment_date,
                'payment_amount'       => $request->payment_amount,
                'remaining_amount'     => $request->remaining_amount,
            ]);
            $invoice_details->update([
                'value_status' =>  '2',
                'status_ar' => 'مدفوعة جزئيا',
                'status_en' => 'partially paid',
                'partial_payment_date' => $request->partial_payment_date,
                'payment_amount'       => $request->payment_amount,
                'remaining_amount'     => $request->remaining_amount,
            ]);
        }elseif ($request->status == '1')
        {
            $invoice->update([
                'value_status' =>  '1',
                'status_ar' => 'مدفوعة كليا',
                'status_en' => 'totally paid',
                'total_payment_date' => $request->total_payment_date,
                'payment_amount'       => $request->payment_amount,
                'remaining_amount'     => $request->remaining_amount,
            ]);
            $invoice_details->update([
                'value_status' =>  '1',
                'status_ar' => 'مدفوعة كليا',
                'status_en' => 'totally paid',
                'total_payment_date' => $request->total_payment_date,
                'payment_amount'       => $request->payment_amount,
                'remaining_amount'     => $request->remaining_amount,
            ]);
        }

        session()->flash('success', trans('projectlang.payment_status_updated_successfully'));
        return redirect()->route('archivedInvoices.index');
    }

    public function restoreArchived(Request $request)
    {
        $id = $request->invoice_id;
        Invoice::withTrashed()->where('id', $id)->restore();
        session()->flash('success', trans('projectlang.invoice_restored_successfully'));
        return redirect()->route('archivedInvoices.index');

    }

    public function archivedForceDelete(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id' , $invoice_id)->with('attachments')->withTrashed()->first();
        $date = $invoice->created_at->format('m-Y');
        $invoiceNumber = $invoice->invoice_number;

        if(!empty($invoice->attachments)){
            foreach ($invoice->attachments as $index => $attachment)
            {
                Storage::disk('uploads')->delete($date.'/'.$invoiceNumber.'/'.$attachment->file_name);
            }
        }

        $FileSystem = new Filesystem();
        $invoiceNumberFolder = Storage::disk('uploads')->path($date.'/'.$invoiceNumber);
        $dateFolder = Storage::disk('uploads')->path($date);

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

        $invoice->forceDelete();

        session()->flash('success', trans('projectlang.invoice_forceDeleted_successfully'));
        return back();
    }

    public function exportArchived()
    {
        return Excel::download(new ArchivedInvoicesExport, 'ArchivedInvoices.xlsx');
    }

    public function exportArchivedPaidInvoices()
    {
        return Excel::download(new ArchivedPaidInvoicesExport, 'ArchivedPaidInvoices.xlsx');
    }

    public function exportArchivedPartiallyPaidInvoices()
    {
        return Excel::download(new ArchivedPartiallyPaidInvoicesExport, 'ArchivedPartiallyPaidInvoices.xlsx');
    }

    public function exportArchivedUnPaidInvoices()
    {
        return Excel::download(new ArchivedUnPaidInvoicesExport, 'ArchivedUnPaidInvoices.xlsx');
    }
}
