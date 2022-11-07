<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\invoice_attatchments;
use App\Models\invoice_details;
use App\Models\Product;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Exports\InvoicesExport;
use App\Exports\PaidInvoicesExport;
use App\Exports\PartiallyPaidInvoicesExport;
use App\Exports\UnPaidInvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.invoices-list'); // set the page title
        $this->data['heading'] = trans('projectlang.invoices-list');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => route('dashboard'),
            trans('projectlang.invoices') => false,
            trans('projectlang.invoices-list') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::with('department', 'product')->get();
        return view('invoices.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = trans('projectlang.add_invoice'); // set the page title
        $this->data['heading'] = trans('projectlang.add_invoice');

        if(str_contains(url()->previous(), '/invoices')) {
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => route('dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.invoices-list') => route('invoices.index'),
                trans('projectlang.add_invoice') => false,
            ];
        }
        if(str_contains(url()->previous(), '/unpaidInvoices')){
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => route('dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.non-paid') => route('unpaidInvoices'),
                trans('projectlang.add_invoice') => false,
            ];
        }
        if(str_contains(url()->previous(), '/partiallyPaidInvoices')){
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => route('dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.partialy-paid') => route('partiallyPaidInvoices'),
                trans('projectlang.add_invoice') => false,
            ];
        }
        if(str_contains(url()->previous(), '/paidInvoices')){
            $this->data['breadcrumbs'] = [
                trans('backpack::crud.admin') => route('dashboard'),
                trans('projectlang.invoices') => false,
                trans('projectlang.paid-invoices') => route('paidInvoices'),
                trans('projectlang.add_invoice') => false,
            ];
        }

        $this->data['departments'] = Department::all();
        $this->data['lang'] = app()->getLocale();
        $invoices = Invoice::all();
        if($invoices->isEmpty()){
            $this->data['newInvoiceNumber'] = date('m-Y') . '-' . (1) ;
        }else{
            $this->data['lastInvoiceID'] = Invoice::orderBy('id', 'DESC')->pluck('id')->first();
            $this->data['newInvoiceNumber'] = date('m-Y') . '-' . ($this->data['lastInvoiceID'] + 1);
        }
        return view('invoices.create', $this->data);
    }

    /**
     * Get the products related to certain department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts($id) {
        $products = Product::where('department_id', $id)->pluck('product_name_'.app()->getLocale(), 'id');
        return json_encode($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('id', $request->product_id)->pluck('id');
//        dd($product[0]);
        Invoice::create([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_date,
            'due_date'          => $request->due_date,
            'department_id'     => $request->department_id,
            'product_id'        => $product[0],
            'collection_amount' => $request->collection_amount,
            'commission_rate'   => $request->commission_rate,
            'commission_amount' => $request->commission_amount,
            'discount_rate'     => $request->discount_rate,
            'discount'          => $request->discount,
            'rate_vat'          => $request->rate_vat,
            'value_vat'         => $request->value_vat,
            'sub_total'         => $request->sub_total,
            'total'             => $request->total,
            'status_ar'         => 'غير مدفوعة',
            'status_en'         => 'unpaid',
            'notes_ar'          => $request->notes_ar,
            'notes_en'          => $request->notes_en
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        invoice_details::create([
            'invoice_id'        => $invoice_id,
            'invoice_number'    => $request->invoice_number,
            'department_id'     => $request->department_id,
            'product_id'        => $product[0],
            'status_ar'         => 'غير مدفوعة',
            'status_en'         => 'unpaid',
            'notes_ar'          => $request->notes_ar,
            'notes_en'          => $request->notes_en,
            'user'              => (backpack_user()->name)
        ]);

        if($request->hasFile('files')){
            $files =$request->file('files');
            foreach ($files as $file) {
                $invoice_id = Invoice::latest()->first()->id;
                $file_name = $file->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                invoice_attatchments::create([
                    'invoice_id' => $invoice_id,
                    'invoice_number' => $invoice_number,
                    'file_name' => $file_name,
                    'created_by' => (backpack_user()->name)
                ]);
                $date_folder = date('m-Y');
                $file->move(public_path('Attachments/' . $date_folder . '/' . $invoice_number), $file_name);
            }
        }

        //$user = \Illuminate\Support\Facades\DB::table('users')->where('roles_name' , '["SuperAdmin"]')->get();
        $user = User::where('roles_name' , '["SuperAdmin"]')->orWhere('roles_name' , '["Admin"]')->get();
        $invoice = Invoice::latest()->first();
//        $user->notify(new \App\Notifications\AddInvoice($invoice));
        Notification::send($user, new \App\Notifications\AddInvoice($invoice));


        session()->flash('success', trans('projectlang.invoice_added_successfully'));
        return redirect()->route('invoices.index');
    }
    /**
     * Mark one notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markOneAsRead($id)
    {
        foreach(backpack_auth()->user()->unreadNotifications as $notification)
        {
            $notification_invoice_id = $id;
            if($notification_invoice_id == $notification->data['data'])
            {
                $notification->markAsRead();
            }
        }
        return redirect()->route('invoiceDetails-show', $notification->data['data']);
    }

    /**
     * Mark one notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markOneEditedAsRead($id)
    {
        foreach(backpack_auth()->user()->unreadNotifications as $notification)
        {
            $notification_invoice_id = $id;
            if($notification_invoice_id == $notification->data['data_edited'])
            {
                $notification->markAsRead();
            }
        }
        return redirect()->route('invoiceDetails-show', $notification->data['data_edited']);
    }

    /**
     * Mark one notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markOneArchivedAsRead($id)
    {
        foreach(backpack_auth()->user()->unreadNotifications as $notification)
        {
            $notification_invoice_id = $id;
            if($notification_invoice_id == $notification->data['data_archived'])
            {
                $notification->markAsRead();
            }
        }
        return redirect()->route('archivedInvoiceDetails-show', $notification->data['data_archived']);
    }

    /**
     * Mark one notification as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markOneDeletedAsRead($id)
    {
        foreach(backpack_auth()->user()->unreadNotifications as $notification)
        {
            $notification_invoice_id = $id;
            if($notification_invoice_id == $notification->data['data_deleted'])
            {
                $notification->markAsRead();
            }
        }
        return redirect()->back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $userUnreadNotifications = backpack_auth()->user()->unreadNotifications;
        if($userUnreadNotifications)
        {
            $userUnreadNotifications->markAsRead();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Display the specified resource.
     */
    public function unpaidInvoices()
    {
        $this->data['title'] = trans('projectlang.non-paid'); // set the page title
        $this->data['heading'] = trans('projectlang.non-paid');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.invoices') => false,
            trans('projectlang.non-paid') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::with('department', 'product')->where('value_status', 3)->get();
        return view('invoices.unpaidInvoices', $this->data);
    }

    /**
     * Display the specified resource.
     */
    public function partiallyPaidInvoices()
    {
        $this->data['title'] = trans('projectlang.partialy-paid'); // set the page title
        $this->data['heading'] = trans('projectlang.partialy-paid');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.invoices') => false,
            trans('projectlang.partialy-paid') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::with('department', 'product')->where('value_status', 2)->get();
        return view('invoices.partiallyPaidInvoices', $this->data);
    }

    /**
     * Display the specified resource.
     */
    public function paidInvoices()
    {
        $this->data['title'] = trans('projectlang.paid-invoices'); // set the page title
        $this->data['heading'] = trans('projectlang.paid-invoices');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.invoices') => false,
            trans('projectlang.paid-invoices') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['invoices'] = Invoice::with('department', 'product')->where('value_status', 1)->get();
        return view('invoices.paidInvoices', $this->data);
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
        $this->data['invoice'] = Invoice::where('id', $id)->with('department', 'product')->first();
        $this->data['departments'] = Department::all();
        $this->data['products'] = Product::all();
        return view('invoices.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
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

        //$user = \Illuminate\Support\Facades\DB::table('users')->where('roles_name' , '["SuperAdmin"]')->get();
        $user = User::where('roles_name' , '["SuperAdmin"]')->orWhere('roles_name' , '["Admin"]')->get();
        Notification::send($user, new \App\Notifications\EditInvoice($invoice));

        session()->flash('success', trans('projectlang.invoice_edited_successfully'));
        return redirect()->route('invoices.index');
    }

    public function paymentUpdate(Request $request)
    {
        $invoice = Invoice::where('id' , $request->invoice_id)->first();
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

        //$user = \Illuminate\Support\Facades\DB::table('users')->where('roles_name' , '["SuperAdmin"]')->get();
        $user = User::where('roles_name' , '["SuperAdmin"]')->orWhere('roles_name' , '["Admin"]')->get();
        Notification::send($user, new \App\Notifications\EditInvoice($invoice));

        session()->flash('success', trans('projectlang.payment_status_updated_successfully'));
        return redirect()->route('invoices.index');
    }

    public function printInvoice($id)
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
        $this->data['invoice'] = Invoice::where('id', $id)->with('department', 'product')->first();
        $this->data['departments'] = Department::all();
        $this->data['products'] = Product::all();
        return view('invoices.printPreview', $this->data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id' , $invoice_id)->with('attachments')->first();
        $invoice->delete();

        //$user = \Illuminate\Support\Facades\DB::table('users')->where('roles_name' , '["SuperAdmin"]')->get();
        $user = User::where('roles_name' , '["SuperAdmin"]')->orWhere('roles_name' , '["Admin"]')->get();
        Notification::send($user, new \App\Notifications\ArchiveInvoice($invoice));

        session()->flash('success', trans('projectlang.invoice_sofDeleted_successfully'));
        return back();

    }

    public function forceDelete(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id' , $invoice_id)->with('attachments')->first();
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

        //$user = \Illuminate\Support\Facades\DB::table('users')->where('roles_name' , '["SuperAdmin"]')->get();
        $user = User::where('roles_name' , '["SuperAdmin"]')->orWhere('roles_name' , '["Admin"]')->get();
        Notification::send($user, new \App\Notifications\DeleteInvoice($invoice));

        session()->flash('success', trans('projectlang.invoice_forceDeleted_successfully'));
        return back();
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function exportPaidInvoices()
    {
        return Excel::download(new PaidInvoicesExport, 'paidInvoices.xlsx');
    }

    public function exportPartiallyPaidInvoices()
    {
        return Excel::download(new PartiallyPaidInvoicesExport, 'partiallyPaidInvoices.xlsx');
    }

    public function exportUnPaidInvoices()
    {
        return Excel::download(new UnPaidInvoicesExport, 'unPaidInvoices.xlsx');
    }
}
