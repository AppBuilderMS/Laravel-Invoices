<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomersReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.customers-report'); // set the page title
        $this->data['heading'] = trans('projectlang.customers-report');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.reports') => false,
            trans('projectlang.customers-report') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['departments'] = Department::all();
        return view('reports.customersReports', $this->data);
    }

    /**
     * Get the products related to certain department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchCustomers(Request $request){
        $this->data['heading'] = trans('projectlang.customers-report');
        $this->data['lang'] = app()->getLocale();
        //No date
        $request->validate([
            'department'      => 'required',
        ]);
        if ($request->department && $request->product && $request->start_at =='' && $request->end_at=='') {
            $this->data['invoices'] = Invoice::with('product', 'department')->where(['department_id' => $request->department, 'product_id' => $request->product])->get();
            $this->data['departments']= Department::all();
            $this->data['products']= Product::all();
            return view('reports.customersReports', $this->data);
        }

        // if date
        else {
            $request->validate([
                'department'      => 'required',
                'start_at'  => 'required',
                'end_at'    => 'required',
            ]);
            $this->data['start_at'] = date($request->start_at);
            $this->data['end_at'] = date($request->end_at);
            $this->data['invoices'] = Invoice::with('product', 'department')->whereBetween('invoice_date',[$this->data['start_at'],$this->data['end_at']])->where(['department_id' => $request->department, 'product_id' => $request->product])->get();
            $this->data['departments']= Department::all();
            $this->data['products']= Product::all();
            return view('reports.customersReports', $this->data);
        }

    }
}
