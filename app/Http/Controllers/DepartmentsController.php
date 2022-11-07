<?php

namespace App\Http\Controllers;


use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.departments'); // set the page title
        $this->data['heading'] = trans('projectlang.departments');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.settings') => false,
            trans('projectlang.departments') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['departments'] = Department::all();
        return view('departments.index', $this->data);
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
        $validator = Validator::make($request->all(),[
            'dep_name_ar' => 'required|unique:departments',
            'dep_name_en' => 'required|unique:departments',
            'desc_ar'     => 'sometimes',
            'desc_en'     => 'sometimes',
        ]);
        if ($validator->fails())
        {
           //dd($validator->errors()->all());
            return redirect(route('departments.index'))->withErrors($validator)->withInput();
        } else {
            Department::create([
                'dep_name_ar'   => $request->dep_name_ar,
                'dep_name_en'   => $request->dep_name_en,
                'desc_ar'       => $request->desc_ar,
                'desc_en'       => $request->desc_en,
                'created_by'    => (backpack_user()->name)
            ]);
            session()->flash('success', trans('projectlang.dep_added_success'));
            return redirect(route('departments.index'));
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
        //dd($request->all());
        $id = $request->dep_id;
        $validator = Validator::make($request->all(),[
            'dep_name_ar' => 'required|unique:departments,dep_name_ar,'.$id,
            'dep_name_en' => 'required|unique:departments,dep_name_en,'.$id,
            'desc_ar'     => 'sometimes',
            'desc_en'     => 'sometimes',
        ]);
        if ($validator->fails())
        {
            //dd($validator->errors()->all());
            return redirect(route('departments.index'))->withErrors($validator)->withInput();
        } else {
            $department = Department::find($id);
            $department->update([
                'dep_name_ar'   => $request->dep_name_ar,
                'dep_name_en'   => $request->dep_name_en,
                'desc_ar'       => $request->desc_ar,
                'desc_en'       => $request->desc_en,
            ]);
            session()->flash('success', trans('projectlang.dep_updated_success'));
            return redirect(route('departments.index'));
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
        $id = $request->dep_id;
        $department = Department::find($id);
        $department->delete();
        session()->flash('success', trans('projectlang.dep_deleted_success'));
        return redirect(route('departments.index'));
    }
}
