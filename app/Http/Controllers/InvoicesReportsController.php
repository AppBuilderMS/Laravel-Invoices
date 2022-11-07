<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.invoices-report'); // set the page title
        $this->data['heading'] = trans('projectlang.invoices-report');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.reports') => false,
            trans('projectlang.invoices-report') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        return view('reports.invoicesReports', $this->data);
    }

    /**
     * Get the products related to certain department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchInvoices(Request $request)
    {
        $this->data['heading'] = trans('projectlang.invoices-report');
        $this->data['lang'] = app()->getLocale();
        $this->data['radio'] = $request->radio;
        //Search by invoice type
        if ($this->data['radio'] == 1) {

            //if no date
            $request->validate([
                'type'      => 'required',
            ]);
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $this->data['invoices'] = Invoice::with('product', 'department')->where('value_status','=',$request->type)->get();
                $this->data['type'] = $request->type;
                if($request->type == 'all'){
                    $this->data['invoices'] = Invoice::with('product', 'department')->get();
                }
                return view('reports.invoicesReports', $this->data);
            }

            // if date
            else {
                $request->validate([
                    'type'      => 'required',
                    'start_at'  => 'required',
                    'end_at'    => 'required',
                ]);
                $this->data['start_at'] = date($request->start_at);
                $this->data['end_at'] = date($request->end_at);
                $this->data['type'] = $request->type;
                $this->data['invoices'] = Invoice::with('product', 'department')->whereBetween('invoice_date',[$this->data['start_at'],$this->data['end_at']])->where('value_status','=',$request->type)->get();
                if($request->type == 'all'){
                    $this->data['invoices'] = Invoice::with('product', 'department')->whereBetween('invoice_date',[$this->data['start_at'],$this->data['end_at']])->get();
                }
                return view('reports.invoicesReports', $this->data);
            }

        }

        //Search by invoice number
        else {
            $request->validate([
                'invoice_number'      => 'required',
            ]);
            $this->data['invoiceNumber'] = $request->invoice_number;
            $this->data['invoices'] = Invoice::with('product', 'department')->where('invoice_number','=',$this->data['invoiceNumber'])->get();
            return view('reports.invoicesReports', $this->data);
        }
    }

}
