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
                                <th class="border-bottom-0">{{trans('projectlang.product_name')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.department_name')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.description')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $index=>$product)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$lang === 'ar' ? $product->product_name_ar : $product->product_name_en}}</td>
                                        <td>{{$lang === 'ar' ? $product->department->dep_name_ar: $product->department->dep_name_en}}</td>
                                        <td>{{$lang === 'ar' ? $product->description_ar : $product->description_en}}</td>
                                        <td>
                                            <button type="button" class="btn btn-ghost-info btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#editModal"
                                                    data-product_id="{{$product->id}}"
                                                    data-product_name_ar="{{$product->product_name_ar}}"
                                                    data-product_name_en="{{$product->product_name_en}}"
                                                    data-product_dep_ar="{{$product->department->dep_name_ar}}"
                                                    data-product_dep_en="{{$product->department->dep_name_en}}"
                                                    data-description_ar="{{$product->description_ar}}"
                                                    data-description_en="{{$product->description_en}}">
                                                <i class="la la-edit"></i> {{trans('projectlang.edit')}}
                                            </button>
                                            <button type="button" class="btn btn-ghost-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal"
                                                    data-product_id="{{$product->id}}"
                                                    data-product_name_ar="{{$product->product_name_ar}}"
                                                    data-product_name_en="{{$product->product_name_en}}">
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
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.add_product')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('products.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="product_name_ar" class="col-form-label">{{trans('projectlang.name_ar')}}</label>
                        <input type="text" class="form-control @error('product_name_ar') is-invalid @enderror" name="product_name_ar" id="product_name_ar" placeholder="{{trans('projectlang.product_name_ar')}}"  value="{{old('product_name_ar')}}">
                        @error('product_name_ar')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="product_name_en" class="col-form-label">{{trans('projectlang.name_en')}}</label>
                        <input type="text" class="form-control @error('product_name_en') is-invalid @enderror" name="product_name_en" id="product_name_en" placeholder="{{trans('projectlang.product_name_en')}}" value="{{old('product_name_en')}}">
                        @error('product_name_en')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="department_id" class="col-form-label">{{trans('projectlang.department_name')}}</label>
                        <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                            <option value="0" disabled selected>{{trans('projectlang.select_dep')}}</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}" {{$department->id === old('department_id') ? 'selected' : ''}}>{{$lang === 'ar' ? $department->dep_name_ar : $department->dep_name_en}}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description_ar" class="col-form-label">{{trans('projectlang.desc_ar')}}</label>
                        <textarea class="form-control" name="description_ar" id="description_ar" placeholder="{{trans('projectlang.product_desc_ar')}}">{{old('description_ar')}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="description_en" class="col-form-label">{{trans('projectlang.desc_en')}}</label>
                        <textarea class="form-control" name="description_en" id="description_en" placeholder="{{trans('projectlang.product_desc_en')}}">{{old('description_en')}}</textarea>
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
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.edit_product')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="products/update" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="product_name_ar" class="col-form-label">{{trans('projectlang.name_ar')}}</label>
                        <input type="text" class="form-control @error('product_name_ar') is-invalid @enderror" name="product_name_ar" id="product_name_ar" placeholder="{{trans('projectlang.product_name_ar')}}">
                        @error('product_name_ar')
                        <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="product_name_en" class="col-form-label">{{trans('projectlang.name_en')}}</label>
                        <input type="text" class="form-control @error('product_name_en') is-invalid @enderror" name="product_name_en" id="product_name_en" placeholder="{{trans('projectlang.product_name_en')}}">
                        @error('product_name_en')
                        <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="department_id" class="col-form-label">{{trans('projectlang.department_name')}}</label>
                        <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                            <option value="0" disabled selected>{{trans('projectlang.select_dep')}}</option>
                            @foreach($departments as $department)
                                <option>{{$lang === 'ar' ? $department->dep_name_ar : $department->dep_name_en}}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description_ar" class="col-form-label">{{trans('projectlang.desc_ar')}}</label>
                        <textarea class="form-control" name="description_ar" id="description_ar" placeholder="{{trans('projectlang.product_desc_ar')}}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description_en" class="col-form-label">{{trans('projectlang.desc_en')}}</label>
                        <textarea class="form-control" name="description_en" id="description_en" placeholder="{{trans('projectlang.product_desc_en')}}"></textarea>
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
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_product')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="products/destroy" method="POST">
                    @method('Delete')
                    @csrf
                    <input type="hidden" name="product_id" id="product_id">
                    @if($lang == 'ar')
                        <div class="form-group">
                            <p class="text-danger font-weight-bold">{{trans('projectlang.delete_product_alert')}}</p>
                            <input type="text" class="form-control" name="product_name_ar" id="product_name_ar" readonly>
                        </div>
                    @else
                        <div class="form-group">
                            <p class="text-danger font-weight-bold">{{trans('projectlang.delete_product_alert')}}</p>
                            <input type="text" class="form-control" name="product_name_en" id="product_name_en" readonly>
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
    <!--Internal Datatable js -->
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
                var product_id = button.data('product_id')
                var product_name_ar = button.data('product_name_ar')
                var product_name_en = button.data('product_name_en')
                var product_dep_ar = button.data('product_dep_ar')
                var product_dep_en = button.data('product_dep_en')
                var description_ar = button.data('description_ar')
                var description_en = button.data('description_en')
                var modal = $(this)
                modal.find('.modal-body #product_id').val(product_id)
                modal.find('.modal-body #product_name_ar').val(product_name_ar)
                modal.find('.modal-body #product_name_en').val(product_name_en)
                @if($lang === 'ar')
                modal.find('.modal-body #department_id').val(product_dep_ar)
                @else
                modal.find('.modal-body #department_id').val(product_dep_en)
                @endif
                modal.find('.modal-body #description_ar').val(description_ar)
                modal.find('.modal-body #description_en').val(description_en)
            });

            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var product_id = button.data('product_id')
                var product_name_ar = button.data('product_name_ar')
                var product_name_en = button.data('product_name_en')
                var modal = $(this)
                modal.find('.modal-body #product_id').val(product_id)
                modal.find('.modal-body #product_name_ar').val(product_name_ar)
                modal.find('.modal-body #product_name_en').val(product_name_en)
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
