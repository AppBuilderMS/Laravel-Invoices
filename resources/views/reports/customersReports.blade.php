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
    <!--Internal Datepicker css -->
    <link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.css') }}" rel="stylesheet">

    <style>
        .dataTables_filter{
            display: none;
        }
    </style>
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
                        @if(authUserPermission('Add an invoice'))
                            <a href="{{route('invoices.create')}}"  class="btn btn-secondary mb-2">
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
                    <form action="{{route('searchCustomers')}}" method="POST" role="search" autocomplete="off">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="department">{{trans('projectlang.department')}}</label>
                                <select name="department" class="form-control">
                                    <option value="" selected disabled>{{trans('projectlang.select_dep')}}</option>
                                    @foreach ($departments as $department)
                                        @if(isset($invoices))
                                            @foreach($invoices as $invoice)
                                                <option value="{{ $department->id }}" {{$department->id === $invoice->department_id ? 'selected' : ''}}> {{$lang === 'ar' ? $department->dep_name_ar :  $department->dep_name_en}}</option>
                                            @endforeach
                                        @else
                                            <option value="{{ $department->id }}"> {{$lang === 'ar' ? $department->dep_name_ar :  $department->dep_name_en}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="product" >{{trans('projectlang.product')}}</label>
                                <select id="product" name="product" class="form-control">
                                    @if(isset($invoices))
                                        @foreach($invoices as $invoice)
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" {{$product->id === $invoice->product_id ? 'selected' : ''}}> {{$lang === 'ar' ? $product->product_name_ar :  $product->product_name_en}}</option>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6" id="start_at">
                                <label for="exampleFormControlSelect1">{{trans('projectlang.from')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control datepicker @if($lang === 'ar') datepicker-ar @endif" value="{{isset($start_at) ? $start_at : old('start_at')}}" name="start_at" placeholder="YYYY-MM-DD" type="text">
                                </div><!-- input-group -->
                            </div>

                            <div class="col-sm-6" id="end_at">
                                <label for="exampleFormControlSelect1">{{trans('projectlang.to')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control datepicker @if($lang === 'ar') datepicker-ar @endif" name="end_at" value="{{isset($end_at) ? $end_at : old('end_at')}}" placeholder="YYYY-MM-DD" type="text">
                                </div><!-- input-group -->
                            </div>
                        </div>

                        <div class="row justify-content-center mb-4">
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary btn-block"><i class="la la-search"></i> {{trans('projectlang.search')}}</button>
                            </div>
                        </div>

                    </form>
                    @if(isset($invoices))
                        <div class="table-responsive export-pages">
                            <table id="example" class="table key-buttons text-md-nowrap table-striped table-hover table-bordered">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{trans('projectlang.invoice_number')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.invoice_date')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.due_date')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.product')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.department')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.discount_rate')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.discount')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.vat_rate')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.vat_amount')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.total')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.status')}}</th>
                                    <th class="border-bottom-0">{{trans('projectlang.notes')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$invoice->invoice_number}}</td>
                                        <td>{{$invoice->invoice_date}}</td>
                                        <td>{{$invoice->due_date}}</td>
                                        <td>{{$lang === 'ar' ? $invoice->product->product_name_ar : $invoice->product->product_name_en}}</td>
                                        <td>{{$lang === 'ar' ? $invoice->department->dep_name_ar : $invoice->department->dep_name_en}}</td>
                                        <td>{{$invoice->discount_rate}}</td>
                                        <td>{{$invoice->discount}}</td>
                                        <td>{{$invoice->rate_vat}}</td>
                                        <td>{{$invoice->value_vat}}</td>
                                        <td>{{$invoice->total}}</td>
                                        <td>{{$lang === 'ar' ? $invoice->status_ar : $invoice->status_en}}</td>
                                        <td>{{$lang === 'ar' ? $invoice->notes_ar : $invoice->notes_en}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-2">
                                <a href="{{{route('customersReports')}}}"  class="btn btn-info btn-block mb-2 ml-1 mr-1">
                                    <i class="la la-search-plus"></i>
                                    {{trans('projectlang.new_search')}}
                                </a>
                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- /row -->

@endsection


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

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('packages/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('packages/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ar.min.js') }}"></script>
    <script>
        var date = $('.datepicker').datepicker({
            // dateFormat: 'yy-mm-dd',
            format: 'yyyy-mm-dd',
            language: @if($lang === 'ar') 'ar' @else 'en' @endif ,
        }).val();
    </script>

    <!--Get the products for each department-->
    <script>
        $(document).ready(function () {
            $('select[name="department"]').on('change', function () {
                var dep_id = $(this).val();
                if(dep_id) {
                    $.ajax({
                        url: "{{\Illuminate\Support\Facades\URL::to('departments')}}/"+dep_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="product"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="product"]').append(
                                    '<option value="'+key+'">'+value+'</option>'
                                );
                            });
                        }
                    });
                }else{
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
