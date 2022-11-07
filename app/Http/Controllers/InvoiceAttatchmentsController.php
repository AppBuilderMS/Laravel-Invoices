<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attatchments;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class InvoiceAttatchmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->hasFile('files')){
            $files =$request->file('files');
            foreach ($files as $file) {
                $invoice_id = $request->invoice_id;
                $file_name = $file->getClientOriginalName();
                $invoice_number = $request->invoice_number;
                $dateFolderRequest = $request->folder_date;

                Invoice_attatchments::create([
                    'invoice_id' => $invoice_id,
                    'invoice_number' => $invoice_number,
                    'file_name' => $file_name,
                    'created_by' => (backpack_user()->name)
                ]);
                $invoiceNumberFolder = Storage::disk('uploads')->path($dateFolderRequest.'/'.$invoice_number);
                $dateFolder = Storage::disk('uploads')->path($dateFolderRequest);
                //Check if the invoiceNumberFolder and dateFolder exists.
                if (!File::exists($invoiceNumberFolder)) {
                    $file->move($invoiceNumberFolder, $file_name);
                }elseif(!File::exists($dateFolder)) {
                    $file->move($invoiceNumberFolder, $file_name);
                }else{
                    $file->move($invoiceNumberFolder, $file_name);
                }
            }
        }

        session()->flash('success', trans('projectlang.attach_added_successfully'));
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice_attatchments  $invoice_attatchments
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice_attatchments $invoice_attatchments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice_attatchments  $invoice_attatchments
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice_attatchments $invoice_attatchments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice_attatchments  $invoice_attatchments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_attatchments $invoice_attatchments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice_attatchments  $invoice_attatchments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice_attatchments $invoice_attatchments)
    {
        //
    }
}
