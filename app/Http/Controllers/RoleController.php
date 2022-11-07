<?php
namespace App\Http\Controllers;

use App\Models\TranslatedPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('role:SuperAdmin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.users-roles'); // set the page title
        $this->data['heading'] = trans('projectlang.users-roles');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-roles') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['roles'] = Role::all();
        return view('roles.index',$this->data);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = trans('projectlang.users-roles'); // set the page title
        $this->data['heading'] = trans('projectlang.add_role');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-roles') => route('roles.index'),
            trans('projectlang.add_role') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['permissions'] = TranslatedPermission::all();
        return view('roles.create',$this->data);
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
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index')->with('success', trans('projectlang.role_added_successfully'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {
        /* $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return view('roles.show',compact('role','rolePermissions'));*/
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = trans('projectlang.edit_user_role'); // set the page title
        $this->data['heading'] = trans('projectlang.edit_user_role');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-roles') => route('roles.index'),
            trans('projectlang.edit_user_role') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['role'] = Role::find($id);
        $this->data['permissions'] = TranslatedPermission::get();
        $this->data['rolePermissions'] = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('roles.edit',$this->data);
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
            'name' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::find($id);
        $role->update(['name' => $request->name]);
        $role->save();
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index')->with('success',trans('projectlang.role_updated_successfully'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role_id = $request->role_id;
        DB::table("roles")->where('id',$role_id)->delete();
        return redirect()->route('roles.index')->with('success',trans('projectlang.role_deleted_successfully'));
    }

}
