@extends(backpack_view('blank'))
@push('after_styles')
    <!--Start of bootstrap-fileinput -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    @if($lang === 'ar')
        <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css">
    @endif
    <!-- the font awesome icon library if using with `fas` theme (or Bootstrap 4.x). Note that default icons used in the plugin are glyphicons that are bundled only with Bootstrap 3.x. -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <!--End of bootstrap-fileinput -->
@endpush
@section('content')
    <h3 class="heading">{{$heading . ' ' .trans('projectlang.number') . ' ' . $invoice_details->invoice_number}}</h3>
        <div class="row animated fadeIn">
            <div class="col-md-12">
                <div class="tab-container  mb-2">

                    <div class="nav-tabs-custom " id="form_tabs">
                        <ul class="nav nav-tabs " role="tablist">
                            <li role="presentation" class="nav-item">
                                <a href="#invoice-info" aria-controls="invoice-info" role="tab" tab_name="invoice-info" data-toggle="tab" class="nav-link active">{{trans('projectlang.invoice-details')}}</a>
                            </li>
                            <li role="presentation" class="nav-item">
                                <a href="#attatchments" aria-controls="attatchments" role="tab" tab_name="attatchments" data-toggle="tab" class="nav-link ">{{trans('projectlang.attachments')}}</a>
                            </li>
                        </ul>

                        <div class="tab-content p-0 ">
                            <div role="tabpanel" class="tab-pane pb-0 active" id="invoice-info">
                                <!-- Default box -->
                                    <div class="card no-padding no-border">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr>
                                                <td style="width: 10%"><strong>{{trans('projectlang.invoice_date')}}:</strong></td>
                                                <td><span>{{$invoice_details->invoice->invoice_date}}</span></td>

                                                <td style="width: 10%"><strong>{{trans('projectlang.due_date')}}:</strong></td>
                                                <td><span>{{$invoice_details->invoice->due_date}}</span></td>
                                            </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.department')}}:</strong></td>
                                                    <td><span>{{$lang === 'ar' ? $invoice_details->invoice->department->dep_name_ar : $invoice_details->invoice->department->dep_name_en}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.product')}}:</strong></td>
                                                    <td><span>{{$lang === 'ar' ? $invoice_details->invoice->product->product_name_ar : $invoice_details->invoice->product->product_name_en}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.created_by')}}:</strong></td>
                                                    <td><span>{{$invoice_details->user}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.created_at')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->created_at}}</span></td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.collection_amount')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->collection_amount}}</span></td>

                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.commission_amount')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->commission_amount}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.commission_rate')}}:</strong></td>
                                                    <td><span>% {{$invoice_details->invoice->commission_rate}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.discount')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->discount}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.discount_rate')}}:</strong></td>
                                                    <td><span>% {{$invoice_details->invoice->discount_rate}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.value_add_tax')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->value_vat}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.vat_rate')}}:</strong></td>
                                                    <td><span>% {{$invoice_details->invoice->rate_vat}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.sub_total')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->sub_total}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.total_include_tax')}}:</strong></td>
                                                    <td><span>{{$invoice_details->invoice->total}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.notes')}}:</strong></td>
                                                    <td><span>{{$lang === 'ar' ? $invoice_details->notes_ar : $invoice_details->notes_en}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.status')}}:</strong></td>
                                                    <td>
                                                        @if($invoice_details->value_status === 1)
                                                            <span class="badge badge-success">{{$lang === 'ar' ? $invoice_details->status_ar : $invoice_details->status_en}}</span>
                                                        @elseif($invoice_details->value_status === 2)
                                                            <span class="badge badge-warning">{{$lang === 'ar' ? $invoice_details->status_ar : $invoice_details->status_en}}</span>
                                                        @else
                                                            <span class=" badge badge-danger">{{$lang === 'ar' ? $invoice_details->status_ar : $invoice_details->status_en}}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.payment_amount')}}:</strong></td>
                                                    <td><span>{{$invoice_details->payment_amount ? $invoice_details->payment_amount : trans('projectlang.unpaid')}}</span></td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.partial_payment_date')}}:</strong></td>
                                                    <td>
                                                        <span>
                                                            @if($invoice_details->partial_payment_date)
                                                                {{$invoice_details->partial_payment_date}}
                                                            @elseif($invoice_details->total_payment_date)
                                                                {{trans('projectlang.totally_paid')}}
                                                            @else
                                                                {{trans('projectlang.unpaid')}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 10%"><strong>{{trans('projectlang.remaining_amount')}}:</strong></td>
                                                    <td>
                                                        <span>@if($invoice_details->remaining_amount)
                                                                {{$invoice_details->remaining_amount}}
                                                            @elseif($invoice_details->value_status == '1')
                                                                {{trans('projectlang.totally_paid')}}
                                                            @elseif($invoice_details->value_status == '3')
                                                                {{trans('projectlang.unpaid')}}
                                                            @endif
                                                        </span>
                                                    </td>

                                                    <td style="width: 10%"><strong>{{trans('projectlang.total_payment_date')}}:</strong></td>
                                                    <td>
                                                        <span>
                                                            @if($invoice_details->total_payment_date)
                                                                {{$invoice_details->total_payment_date}}
                                                            @elseif($invoice_details->partial_payment_date)
                                                                {{trans('projectlang.partially_paid')}}
                                                            @else
                                                                {{trans('projectlang.unpaid')}}
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->
                            </div>

                            <div role="tabpanel" class="tab-pane pb-0" id="attatchments">

                                <form action="{{route('invoiceAttachments.store')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                                    <input type="hidden" name="invoice_number" value="{{$invoice->invoice_number}}">
                                    <input type="hidden" name="folder_date" value="{{$invoice->created_at->format('m-Y')}}">
                                    <div class="col-sm-12 col-md-12">
                                        <input id="input-id" type="file" name="files[]" class="file" multiple data-preview-file-type="text">
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button type="submit" class="btn btn-primary">{{trans('projectlang.save_data')}}</button>
                                    </div>
                                </form>

                                <div class="card no-padding no-border">
                                    <table class="table table-striped mb-0">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>{{trans('projectlang.file_name')}}</th>
                                                <th>{{trans('projectlang.created_by')}}</th>
                                                <th>{{trans('projectlang.created_at')}}</th>
                                                <th>{{trans('projectlang.actions')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($invoice->attachments as $index => $attachment)
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{$attachment->file_name}}</td>
                                                <td>{{$attachment->created_by}}</td>
                                                <td>{{$attachment->created_at}}</td>
                                                <td>
                                                    <a href="{{route('archivedFilePreview', [$invoice->created_at->format('m-Y'), $attachment->invoice_number, $attachment->file_name])}}" class="btn btn-ghost-info btn-sm" target="_blank">
                                                        <i class="la la-eye"></i>
                                                        {{trans('projectlang.preview')}}
                                                    </a>
                                                    <a href="{{route('archivedFileDownload', [$invoice->created_at->format('m-Y'), $attachment->invoice_number, $attachment->file_name])}}" class="btn btn-ghost-success btn-sm">
                                                        <i class="la la-download"></i>
                                                        {{trans('projectlang.download')}}
                                                    </a>
                                                    <button
                                                        class="btn btn-ghost-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal"
                                                        data-attach_id="{{$attachment->id}}"
                                                        data-folder_date="{{$attachment->created_at->format('m-Y')}}"
                                                        data-invoice_number="{{$attachment->invoice_number}}"
                                                        data-file_name="{{$attachment->file_name}}">
                                                        <i class="la la-trash-alt"></i>
                                                        {{trans('projectlang.delete')}}
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
                </div>
            </div>
        </div>

@endsection

<!--Start Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="direction: {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'rtl' : 'ltr'}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('projectlang.delete_attach')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('fileDelete')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <p class="text-danger font-weight-bold">{{trans('projectlang.delete_attach_alert')}}</p>
                        <input type="hidden" name="attach_id" id="attach_id">
                        <input type="hidden" class="form-control" name="folder_date" id="folder_date" readonly>
                        <input type="hidden" class="form-control" name="invoice_number" id="invoice_number" readonly>
                        <input type="text" class="form-control" name="file_name" id="file_name" readonly>
                    </div>

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
    <!--Start of bootstrap-fileinput -->
        <!-- the main fileinput plugin file -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/js/fileinput.min.js"></script>
        <!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/themes/fas/theme.min.js"></script>
        @if($lang === 'ar')
            <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.9/js/locales/ar.js"></script>
        @endif
        <script>
            // with plugin options
            $("#input-id").fileinput({
                rtl: true,
                language: 'ar',
                theme: "fas",
                showUpload: false,
                previewFileType: 'any',
                allowedFileTypes: ['image', "pdf"],
                allowedFileExtensions: ["jpg", "gif", "png", "jpeg", "txt", "xls", "doc", "pdf", "txt"],
                maxTotalFileCount: 5,
                layoutTemplates: {
                    actions:
                        '<div class="file-actions">'+
                        '<div class="file-footer-buttons">'+
                        '<button type="button" class="kv-file-remove btn btn-sm btn-kv btn-outline-secondary mr-1 ml-1" title="Remove file">'+
                        '<i class="fas fa-trash-alt"></i>'+
                        '</button>'+
                        '<button type="button" class="kv-file-zoom btn btn-sm btn-kv btn-outline-secondary mr-1 ml-1" title="View Details">'+
                        '<i class="fas fa-search-plus"></i>'+
                        '</button>'+
                        '</div>'+
                        '</div>'
                }
            });
        </script>
    <!--End of bootstrap-fileinput -->

    <script>
        $(document).ready(function () {
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var attach_id = button.data('attach_id')
                var folder_date = button.data('folder_date')
                var invoice_number = button.data('invoice_number')
                var file_name = button.data('file_name')
                var modal = $(this)
                modal.find('.modal-body #attach_id').val(attach_id)
                modal.find('.modal-body #folder_date').val(folder_date)
                modal.find('.modal-body #invoice_number').val(invoice_number)
                modal.find('.modal-body #file_name').val(file_name)
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

