@extends(backpack_view('blank'))
@section('content')
    <h1 class="heading">{{$heading}}</h1>

    <div class="container-fluid animated fadeIn">
        <div class="row">
            <div class="col-md-8 bold-labels">
                <form action="{{route('roles.update', $role->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        <div class="card-body row">
                            <!-- text input -->
                            <div class="form-group col-sm-12 required">
                                <label>{{trans('projectlang.name')}}</label>
                                <input type="text" name="name" value="{{$role->name}}" class="form-control">
                            </div>

                            <!-- roles & permissions -->
                            <div class="form-group col-sm-12 checklist_dependency">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>{{trans('projectlang.user_permissions')}}</label>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            @if(in_array($permission->permission_id, $rolePermissions))
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label class="font-weight-normal">
                                                            <input type="checkbox" label="Permission" checked name="permissions[]" value="{{$permission->permission_id}}">
                                                            {{$lang == 'ar' ? $permission->name_ar : $permission->name_en}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-sm-4">
                                                    <div class="checkbox">
                                                        <label class="font-weight-normal">
                                                            <input type="checkbox" label="Permission" name="permissions[]" value="{{$permission->permission_id}}">
                                                            {{$lang == 'ar' ? $permission->name_ar : $permission->name_en}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div><!-- /.container -->

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{trans('projectlang.save_data')}}</button>
                            <a href="/" class="btn btn-default">{{trans('projectlang.cancel')}}</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <!--Display noty alerts-->
    <script>
        @if(session()->has('success'))
        new Noty({
            type: "success",
            layout: @if($lang === 'ar') 'topLeft' @else 'topRight' @endif ,
            text: '{{session()->get('success')}}'
        }).show();
        @endif

        @if ($errors->any())
        @foreach($errors->all() as $error)
        new Noty({
            type: "error",
            layout: @if($lang === 'ar') 'topLeft' @else 'topRight' @endif ,
            text: '{{$error}}'
        }).show();
        @endforeach
        @endif
    </script>
@endpush
