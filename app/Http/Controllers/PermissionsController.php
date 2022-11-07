<?php

namespace App\Http\Controllers;

use App\Models\TranslatedPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Show permission', ['only' => ['index']]);
        $this->middleware('permission:Add permission', ['only' => ['create','store']]);
        $this->middleware('permission:Edit permission', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete permission', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.users-permisions'); // set the page title
        $this->data['heading'] = trans('projectlang.users-permisions');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-permisions') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['permissions'] = TranslatedPermission::orderBy('id', 'DESC')->get();
        return view('permissions.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = trans('projectlang.create_permission'); // set the page title
        $this->data['heading'] = trans('projectlang.create_permission');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-permisions') => route('permissions.index'),
            trans('projectlang.create_permission') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['permissions'] = TranslatedPermission::all();
        return view('permissions.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);

        Permission::create([
           'name' => $request->name_en
        ]);
        TranslatedPermission::create([
            'name_ar' => $request->name_ar,
            'name_en' => Permission::latest()->first()->name,
            'permission_id' => Permission::latest()->first()->id,
        ]);
        return redirect()->route('permissions.index')->with('success', trans('projectlang.permission_created_successfully'));
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
        $this->data['title'] = trans('projectlang.edit_permission'); // set the page title
        $this->data['heading'] = trans('projectlang.edit_permission');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-permisions') => route('permissions.index'),
            trans('projectlang.edit_permission') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['permission'] = TranslatedPermission::find($id);
        return view('permissions.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);
        $transPermission = TranslatedPermission::find($id);
        $originalPermission = Permission::where('id', $transPermission->permission_id)->first();
        $originalPermission->update([
            'name' => $request->name_en
        ]);
        $originalPermission->save();
        $transPermission->update([
            'name_ar' => $request->name_ar,
            'name_en' => $originalPermission->name,
        ]);
        $transPermission->save();
        return redirect()->route('permissions.index')->with('success', trans('projectlang.permission_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $permission = TranslatedPermission::where('id', $request->permission_id)->first();
        $originalPermission_id = $permission->permission_id;
        DB::table("permissions")->where('id',$originalPermission_id)->delete();
        DB::table("translated_permissions")->where('permission_id',$originalPermission_id)->delete();
        return redirect()->route('permissions.index')->with('success',trans('projectlang.permission_deleted_successfully'));
    }
}
