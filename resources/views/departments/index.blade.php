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
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary mb-2" data-toggle="modal" data-target="#createModal" data-whatever="@mdo">
                            <i class="fas fa-plus fa-sm"></i>
                            {{trans('projectlang.add')}}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive export-pages">
                        <table id="example" class="table key-buttons text-md-nowrap table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{trans('projectlang.dep_name')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.description')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $index=>$dep)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$lang === 'ar' ? $dep->dep_name_ar : $dep->dep_name_en}}</td>
                                        <td>{{$lang === 'ar' ? $dep->desc_ar : $dep->desc_en}}</td>
                                        <td>
                                            <button type="button" class="btn btn-ghost-info btn-sm"
                                                data-toggle="modal"
                                                data-target="#editModal"
                                                data-dep_id="{{$dep->id}}"
                                                data-dep_name_ar="{{$dep->dep_name_ar}}"
                                                data-dep_name_en="{{$dep->dep_name_en}}"
                                                data-desc_ar="{{$dep->desc_ar}}"
                                                data-desc_en="{{$dep->desc_en}}">
                                                <i class="la la-edit"></i> {{trans('projectlang.edit')}}
                                            </button>
                                            <button type="button" class="btn btn-ghost-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-dep_id="{{$dep->id}}"
                                                data-dep_name_ar="{{$dep->dep_name_ar}}"
                                                data-dep_name_en="{{$dep->dep_name_en}}">
                                                <i class="la la-trash"></i> {{trans('projectlang.delete')}}
                                            </button>
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

<!--Start Create Modal-->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.add_dep')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('departments.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="dep_name_ar" class="col-form-label">{{trans('projectlang.name_ar')}}</label>
                        <input type="text" class="form-control @error('dep_name_ar') is-invalid @enderror" name="dep_name_ar" id="dep_name_ar" placeholder="{{trans('projectlang.dep_name_ar')}}"  value="{{old('dep_name_ar')}}">
                        @error('dep_name_ar')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="dep_name_en" class="col-form-label">{{trans('projectlang.name_en')}}</label>
                        <input type="text" class="form-control @error('dep_name_en') is-invalid @enderror" name="dep_name_en" id="dep_name_en" placeholder="{{trans('projectlang.dep_name_en')}}" value="{{old('dep_name_en')}}">
                        @error('dep_name_en')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="desc_ar" class="col-form-label">{{trans('projectlang.desc_ar')}}</label>
                        <textarea class="form-control" name="desc_ar" id="desc_ar" placeholder="{{trans('projectlang.dep_desc_ar')}}">{{old('desc_ar')}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="desc_en" class="col-form-label">{{trans('projectlang.desc_en')}}</label>
                        <textarea class="form-control" name="desc_en" id="desc_ar" placeholder="{{trans('projectlang.dep_desc_en')}}">{{old('desc_en')}}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit"  class="btn btn-primary">{{trans('projectlang.add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End Create Modal-->
<!--Start Edit Modal-->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.edit_dep')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="departments/update" method="POST">
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="dep_id" id="dep_id">
                    <div class="form-group">
                        <label for="dep_name_ar" class="col-form-label">{{trans('projectlang.name_ar')}}</label>
                        <input type="text" class="form-control @error('dep_name_ar') is-invalid @enderror" name="dep_name_ar" id="dep_name_ar" placeholder="{{trans('projectlang.dep_name_ar')}}">
                        @error('dep_name_ar')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="dep_name_en" class="col-form-label">{{trans('projectlang.name_en')}}</label>
                        <input type="text" class="form-control @error('dep_name_en') is-invalid @enderror" name="dep_name_en" id="dep_name_en" placeholder="{{trans('projectlang.dep_name_en')}}">
                        @error('dep_name_en')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="desc_ar" class="col-form-label">{{trans('projectlang.desc_ar')}}</label>
                        <textarea class="form-control" name="desc_ar" id="desc_ar" placeholder="{{trans('projectlang.dep_desc_ar')}}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="desc_en" class="col-form-label">{{trans('projectlang.desc_en')}}</label>
                        <textarea class="form-control" name="desc_en" id="desc_en" placeholder="{{trans('projectlang.dep_desc_en')}}"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit"  class="btn btn-primary">{{trans('projectlang.edit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End Edit Modal-->
<!--Start Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_dep')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="departments/destroy" method="POST">
                    @method('Delete')
                    @csrf
                    <input type="hidden" name="dep_id" id="dep_id">
                    @if($lang == 'ar')
                        <div class="form-group">
                            <p class="text-danger font-weight-bold">{{trans('projectlang.delete_dep_alert')}}</p>
                            <input type="text" class="form-control" name="dep_name_ar" id="dep_name_ar" readonly>
                        </div>
                    @else
                        <div class="form-group">
                            <p class="text-danger font-weight-bold">{{trans('projectlang.delete_dep_alert')}}</p>
                            <input type="text" class="form-control" name="dep_name_en" id="dep_name_en" readonly>
                        </div>
                    @endif

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit"  class="btn btn-danger">{{trans('projectlang.delete')}}</button>
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

    <!--Print column data in the modals-->
    <script>
        $(document).ready(function () {
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var dep_id = button.data('dep_id')
                var dep_name_ar = button.data('dep_name_ar')
                var dep_name_en = button.data('dep_name_en')
                var desc_ar = button.data('desc_ar')
                var desc_en = button.data('desc_en')
                var modal = $(this)
                modal.find('.modal-body #dep_id').val(dep_id)
                modal.find('.modal-body #dep_name_ar').val(dep_name_ar)
                modal.find('.modal-body #dep_name_en').val(dep_name_en)
                modal.find('.modal-body #desc_ar').val(desc_ar)
                modal.find('.modal-body #desc_en').val(desc_en)
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var dep_id = button.data('dep_id')
                var dep_name_ar = button.data('dep_name_ar')
                var dep_name_en = button.data('dep_name_en')
                var modal = $(this)
                modal.find('.modal-body #dep_id').val(dep_id)
                modal.find('.modal-body #dep_name_ar').val(dep_name_ar)
                modal.find('.modal-body #dep_name_en').val(dep_name_en)
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
