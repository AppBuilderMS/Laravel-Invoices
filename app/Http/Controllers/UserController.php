<?php
namespace App\Http\Controllers;
use App\Models\TranslatedPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Users', ['only' => ['index']]);
        $this->middleware('permission:Add a user', ['only' => ['create','store']]);
        $this->middleware('permission:Edit a user', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete a user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = trans('projectlang.users-list'); // set the page title
        $this->data['heading'] = trans('projectlang.users-list');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-list') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['users'] = User::all();
        return view('users.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = trans('projectlang.add_user'); // set the page title
        $this->data['heading'] = trans('projectlang.add_user');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-list') => route('users.index'),
            trans('projectlang.add_user') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $this->data['roles'] = Role::all();
        $this->data['permissions'] = TranslatedPermission::all();
        return view('users.create' ,$this->data);

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
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|same:password_confirmation',
            'status_name'   => 'required',
            'roles_name'    => 'required',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles_name'));
        return redirect()->route('users.index')->with('success',trans('projectlang.user_added_successfully'));
    }

    /**
     * Get the permissions related to certain role
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function getPermissions($id) {
        $permissions = permissionsOfRole($id);
        return json_encode($permissions);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = trans('projectlang.edit_user'); // set the page title
        $this->data['heading'] = trans('projectlang.edit_user');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => url('/'),
            trans('projectlang.users') => false,
            trans('projectlang.users-list') => route('users.index'),
            trans('projectlang.edit_user') => false,
        ];
        $this->data['lang'] = app()->getLocale();
        $user = User::find($id);
        $this->data['user'] = User::find($id);
        $this->data['roles'] = Role::pluck('name','id')->all();
        $this->data['userRole'] = $user->roles_name;
        return view('users.edit', $this->data);
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:password_confirmation',
            'status_name'   => 'required',
            'roles_name'    => 'required',
        ]);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')->with('success',trans('projectlang.user_edited_successfully'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::find($request->user_id)->delete();
        return redirect()->route('users.index')->with('success','تم حذف المستخدم بنجاح');
    }
}
