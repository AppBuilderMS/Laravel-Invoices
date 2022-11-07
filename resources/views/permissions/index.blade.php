@extends(backpack_view('blank'))

@push('after_styles')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('custome/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('custome/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('custome/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('custome/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('custome/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('custome/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('custome/plugins/datatable/_dropdown.css')}}" rel="stylesheet">
@endpush

@section('content')

    <h3 class="heading">{{$heading}}</h3>

    <!-- row opened -->
    <div class="row row-sm">

        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-start">
                        @if(authUserPermission('Add permission'))
                            <a href="{{route('permissions.create')}}"  class="btn btn-secondary mb-2">
                                <i class="fas fa-plus fa-sm"></i>
                                {{trans('projectlang.add')}}
                            </a>
                        @endif
                        @if(authUserPermission('Export Excel'))
                            <a href="#"  class="btn btn-success mb-2 ml-1 mr-1">
                                <i class="fas fa-file-excel"></i>
                                {{trans('projectlang.excel')}}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive export-pages">
                        <table id="example" class="table key-buttons text-md-nowrap table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">{{trans('projectlang.permission_name')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $index => $permission)
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{$lang == 'ar' ? $permission->name_ar : $permission->name_en}}</td>
                                    <td>
                                        @if(authUserPermission('Edit permission'))
                                            <a href="{{route('permissions.edit', $permission->id)}}" class="btn btn-ghost-info btn-sm">
                                                <i class="la la-edit"></i> {{trans('projectlang.edit')}}
                                            </a>
                                        @endif
                                        @if(authUserPermission('Delete permission'))
                                            <button class="btn btn-ghost-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-permission_id="{{$permission->id}}"
                                                    data-permission_name_ar="{{$permission->name_ar}}"
                                                    data-permission_name_en="{{$permission->name_en}}">
                                                <i class="la la-trash-alt"></i> {{trans('projectlang.delete')}}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->

    </div>
    <!-- /row -->

@endsection

<!--Start Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_permission')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('permissions.destroy', 'test')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <p class="text-danger font-weight-bold">{{trans('projectlang.delete_role_alert')}}</p>
                        <input type="hidden" class="form-control" name="permission_id" id="permission_id" readonly>
                        <input type="{{$lang == 'ar' ? 'text' : 'hidden'}}" class="form-control" name="permission_name_ar" id="permission_name_ar" readonly>
                        <input type="{{$lang == 'en' ? 'text' : 'hidden'}}" class="form-control" name="permission_name_en" id="permission_name_en" readonly>

                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit" class="btn btn-danger">{{trans('projectlang.delete')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End Delete Modal-->

@push('after_scripts')
    <!-- Internal Data tables -->
    <script src="{{URL::asset('custome/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/buttons.print.min.js')}}"></script>
    {{--<script src="{{URL::asset('custome/plugins/datatable/js/buttons.colVis.min.js')}}"></script>--}}
    <script src="{{URL::asset('custome/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('custome/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
        <script src="{{URL::asset('custome/plugins/datatable/js/table-data-ar.js')}}"></script>
    @else
        <script src="{{URL::asset('custome/plugins/datatable/js/table-data.js')}}"></script>
    @endif

    <script>
        $(document).ready(function () {
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var permission_id = button.data('permission_id')
                var permission_name_ar = button.data('permission_name_ar')
                var permission_name_en = button.data('permission_name_en')
                var modal = $(this)
                modal.find('.modal-body #permission_id').val(permission_id);
                modal.find('.modal-body #permission_name_ar').val(permission_name_ar);
                modal.find('.modal-body #permission_name_en').val(permission_name_en);
            });
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
