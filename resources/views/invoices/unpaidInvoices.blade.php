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
                        <a href="{{route('invoices.create')}}"  class="btn btn-secondary mb-2">
                            <i class="fas fa-plus fa-sm"></i>
                            {{trans('projectlang.add')}}
                        </a>
                        <a href="{{route('UnPaidInvoicesExportExcel')}}"  class="btn btn-success mb-2 ml-1 mr-1">
                            <i class="fas fa-file-excel"></i>
                            {{trans('projectlang.excel')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                                <th class="border-bottom-0">{{trans('projectlang.total')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.status')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.notes')}}</th>
                                <th class="border-bottom-0">{{trans('projectlang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $index => $invoice)
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>{{$invoice->invoice_date}}</td>
                                    <td>{{$invoice->due_date}}</td>
                                    {{--<td>{{\App\Models\Product::where('id', $invoice->product_id)->pluck('product_name_'.$lang)->first()}}</td>--}}
                                    <td>{{$lang === 'ar' ? $invoice->product->product_name_ar : $invoice->product->product_name_en}}</td>
                                    <td>{{$lang === 'ar' ? $invoice->department->dep_name_ar : $invoice->department->dep_name_en}}</td>
                                    <td>{{$invoice->total}}</td>
                                    <td>
                                        @if($invoice->value_status === 1)
                                            <span class="badge badge-success">{{$lang === 'ar' ? $invoice->status_ar : $invoice->status_en}}</span>
                                        @elseif($invoice->value_status === 2)
                                            <span class="badge badge-warning">{{$lang === 'ar' ? $invoice->status_ar : $invoice->status_en}}</span>
                                        @else
                                            <span class=" badge badge-danger">{{$lang === 'ar' ? $invoice->status_ar : $invoice->status_en}}</span>
                                        @endif
                                    </td>
                                    <td>{{$lang === 'ar' ? $invoice->notes_ar : $invoice->notes_en}}</td>

                                    <td>
                                        <a href="{{route('invoiceDetails-show', $invoice->id)}}" class="btn btn-ghost-info btn-sm" target="_blank">
                                            <i class="la la-eye"></i> {{trans('projectlang.preview')}}
                                        </a>
                                        <a href="{{route('invoices.edit', $invoice->id)}}" class="btn btn-ghost-info btn-sm">
                                            <i class="la la-edit"></i> {{trans('projectlang.edit')}}
                                        </a>
                                        <a href="{{route('printInvoice', $invoice->id)}}" class="btn btn-ghost-info btn-sm">
                                            <i class="la la-print"></i> {{trans('projectlang.print')}}
                                        </a>
                                        <button class="btn btn-ghost-warning btn-sm"
                                                data-toggle="modal"
                                                data-target="#archiveModal"
                                                data-invoice_id_archive="{{$invoice->id}}"
                                                data-invoice_number_archive="{{$invoice->invoice_number}}">
                                            <i class="la la-archive"></i> {{trans('projectlang.archive_invoice')}}
                                        </button>
                                        <button class="btn btn-ghost-danger btn-sm"
                                                data-toggle="modal"
                                                data-target="#deleteModal"
                                                data-invoice_id="{{$invoice->id}}"
                                                data-invoice_number="{{$invoice->invoice_number}}">
                                            <i class="la la-trash-alt"></i> {{trans('projectlang.delete')}}
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

<!--Start Archive Modal-->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_invoice')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('invoices.destroy', 'test')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <p class="text-danger font-weight-bold">{{trans('projectlang.archive_invoice_alert')}}</p>
                        <input type="hidden" class="form-control" name="invoice_id" id="invoice_id_archive" readonly>
                        <input type="text" class="form-control" name="invoice_number" id="invoice_number_archive" readonly>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit"  class="btn btn-warning">{{trans('projectlang.archive_invoice')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End Archive Modal-->

<!--Start Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_invoice')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('invoices.destroy', 'test')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <p class="text-danger font-weight-bold">{{trans('projectlang.delete_invoice_alert')}}</p>
                        <input type="hidden" class="form-control" name="invoice_id" id="invoice_id" readonly>
                        <input type="hidden" class="form-control" name="value_status" id="value_status" readonly>
                        <input type="text" class="form-control" name="invoice_number" id="invoice_number" readonly>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('projectlang.close')}}</button>
                        <button type="submit" id="archiveBtn" class="btn btn-warning">{{trans('projectlang.archive_invoice')}}</button>
                    </div>
                </form>

                <form action="{{route('invoices.forceDelete', 'test')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="invoice_id" id="invoice_id_force" readonly>
                        <input type="hidden" class="form-control" name="invoice_number" id="invoice_number_force" readonly>
                    </div>

                    <div class="form-group forceDeleteBtn">
                        <button type="submit" class="btn btn-danger">{{trans('projectlang.forceDelete')}}</button>
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
            $('#archiveModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var invoice_id = button.data('invoice_id_archive')
                var invoice_number = button.data('invoice_number_archive')
                var modal = $(this)
                modal.find('.modal-body #invoice_id_archive').val(invoice_id)
                modal.find('.modal-body #invoice_number_archive').val(invoice_number)
            });
        })
        $(document).ready(function () {
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var invoice_id = button.data('invoice_id')
                var invoice_number = button.data('invoice_number')
                var value_status = button.data('value_status')
                var modal = $(this)
                modal.find('.modal-body #invoice_id').val(invoice_id);
                modal.find('.modal-body #invoice_id_force').val(invoice_id);
                modal.find('.modal-body #invoice_number').val(invoice_number);
                modal.find('.modal-body #invoice_number_force').val(invoice_number);

                /* if(value_status !== 1) {
                    $('#archiveBtn').css('display', 'none')
                    $('.forceDeleteBtn').css({'right':'79px', 'left': '91px'})
                }else {
                    $('#archiveBtn').css('display', 'inline-block')
                    $('.forceDeleteBtn').css({'right': '141px', 'left': '181px'})
                }*/

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
