<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.products'); // set the page title
        $this->data['heading'] = trans('projectlang.products');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.settings') => false,
            trans('projectlang.products') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['departments'] = Department::all();
        $this->data['products']  = Product::with('department')->get();
        return view('products.index', $this->data);
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
        //dd($request->all());
        $validator = Validator::make($request->all(),[
            'product_name_ar'   => 'required',
            'product_name_en'   => 'required',
            'department_id'     => 'required',
            'description_ar'    => 'sometimes',
            'description_en'    => 'sometimes',
        ]);
        if ($validator->fails())
        {
            //dd($validator->errors()->all());
            return redirect(route('products.index'))->withErrors($validator)->withInput();
        } else {
            Product::create([
                'product_name_ar'       => $request->product_name_ar,
                'product_name_en'       => $request->product_name_en,
                'department_id'         => $request->department_id,
                'description_ar'        => $request->description_ar,
                'description_en'        => $request->description_en,
            ]);
            session()->flash('success', trans('projectlang.product_added_success'));
            return redirect(route('products.index'));
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
        //
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
//        return $request;
        $product_id  = $request->product_id;

//        if(app()->getLocale() == 'ar'){
//            $product_dep_id = Department::where('dep_name_ar', $request->department_id)->first()->id;
//        }else{
//            $product_dep_id = Department::where('dep_name_en', $request->department_id)->first()->id;
//        }

        $product_dep_id = Department::where('dep_name_'.app()->getLocale() , $request->department_id)->first()->id;

        $validator = Validator::make($request->all(),[
            'product_name_ar'   => 'required',
            'product_name_en'   => 'required',
            'department_id'     => 'required',
            'description_ar'    => 'sometimes',
            'description_en'    => 'sometimes',
        ]);
        if ($validator->fails())
        {
            //dd($validator->errors()->all());
            return redirect(route('products.index'))->withErrors($validator)->withInput();
        } else {
            $product = Product::findOrFail($product_id);
            $product->update([
                'product_name_ar'   => $request->product_name_ar,
                'product_name_en'   => $request->product_name_en,
                'department_id'     => $product_dep_id,
                'description_ar'    => $request->description_ar,
                'description_en'    => $request->description_en,
            ]);
            session()->flash('success', trans('projectlang.product_updated_success'));
            return redirect(route('products.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //return $request->all();
        $product = Product::findOrFail($request->product_id);
        $product->delete();
        session()->flash('success', trans('projectlang.product_deleted_success'));
        return redirect(route('products.index'));

    }
}
