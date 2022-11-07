@extends(backpack_view('blank'))
@push('after_styles')
    <style>
        .perm_name{
            margin-bottom: 1px;
        }
    </style>
@endpush
@section('content')
    <h1 class="heading">{{$heading}}</h1>

    <div class="row">
        <div class="col-md-8 bold-labels">
            <form action="{{route('users.update', $user->id)}}" method="post">
                @csrf
                @method('PATCH')
                <div class="card">
                    <div class="card-body row">
                        <!-- text input -->
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.name')}}</label>
                            <input type="text" name="name" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- text input -->
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.email')}}</label>
                            <input type="email" name="email" value="{{$user->email}}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- password -->
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.password')}}</label>
                            <input type="password" name="password" autocomplete="off" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- password confirmation-->
                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.password_confirmation')}}</label>
                            <input type="password" name="password_confirmation" autocomplete="off" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12 required">
                            <label>{{trans('projectlang.status')}}</label>
                            <select name="status_name" class="form-control @error('status_name') is-invalid @enderror">
                                <option value="0" disabled selected>{{trans('projectlang.choose_user_status')}}</option>
                                <option value="active" {{$user->status_name == 'active' ? 'selected' : ''}}>{{trans('projectlang.active')}}</option>
                                <option value="nonactive" {{$user->status_name == 'nonactive' ? 'selected' : ''}}>{{trans('projectlang.non_active')}}</option>
                            </select>
                            @error('status_name')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- roles & permissions -->
                        <div class="form-group col-sm-12 checklist_dependency">
                            <label>{{trans('projectlang.user_role_and_permissions')}}</label>

                            <div class="container">

                                <!--roles-->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>{{trans('projectlang.user_role')}}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach($roles as $key=>$role)
                                        @if(in_array($role, $userRole))
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label class="font-weight-normal">
                                                        <input type="checkbox" id="{{$key}}" checked name="roles_name[]" value="{{$role}}">
                                                        {{$role}}
                                                    </label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label class="font-weight-normal">
                                                        <input type="checkbox" id="{{$key}}" name="roles_name[]" value="{{$role}}">
                                                        {{$role}}
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <!--roles-->
                            </div><!-- /.container -->


                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{trans('projectlang.edit')}}</button>
                        <a href="/" class="btn btn-default">{{trans('projectlang.cancel')}}</a>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{trans('projectlang.permissions_preview')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <select name="role_name" id="" class="form-control role_name mb-2">
                                <option value=""></option>
                                <option value="" selected disabled>{{trans('projectlang.choose_user_role_to_preview_perm')}}</option>
                                @foreach($roles as $key=>$role)
                                    <option value="{{$key}}">{{$role}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-row flex-wrap permissions">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after_scripts')
    <script>
        $(document).ready(function () {
            $('.role_name').on('change', function () {
                var role_id = $(this).val();
                if(role_id) {
                    $.ajax({
                        url: "{{\Illuminate\Support\Facades\URL::to('roles')}}/"+role_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            // console.log(data);
                            $('.permissions').empty();
                            $.each(data, function (key, value) {
                                $('.permissions').append(
                                    '<a href="#" class="card perm_name p-2 w-50 list-group-item-action">'+value+'</a>'
                                );
                            });
                        }
                    });
                }else{
                    $('.permissions').empty();
                    console.log('Ajax load not working')
                }
            })
        })
    </script>


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
