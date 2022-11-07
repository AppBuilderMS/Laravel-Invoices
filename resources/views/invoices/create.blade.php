@extends(backpack_view('blank'))
@push('after_styles')
    <!--Internal Datepicker css -->
    <link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.css') }}" rel="stylesheet">

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
    <h3 class="heading">{{$heading}}</h3>

    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        {{-- 1 --}}
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="invoice_number" class="control-label">{{trans('projectlang.invoice_number')}}</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{$newInvoiceNumber}}" readonly>
                            </div>

                            <div class="col-sm-4">
                                <label for="invoice_Date" class="control-label">{{trans('projectlang.invoice_date')}}</label>
                                <input class="form-control" name="invoice_date" placeholder="YYYY-MM-DD" type="text" value="{{ date('Y-m-d') }}" required readonly>
                            </div>

                            <div class="col-sm-4">
                                <label for="due_date" class="control-label">{{trans('projectlang.due_date')}}</label>
                                <input class="form-control datepicker @if($lang === 'ar') datepicker-ar @endif" id="due_date" name="due_date" placeholder="YYYY-MM-DD" type="text" required>
                            </div>
                        </div>

                        {{-- 2 --}}
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="inputName" class="control-label">{{trans('projectlang.department')}}</label>
                                <select name="department_id" class="form-control">
                                    <!--placeholder-->
                                    <option value="" selected disabled>{{trans('projectlang.select_dep')}}</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"> {{$lang === 'ar' ? $department->dep_name_ar :  $department->dep_name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="product_id" class="control-label">{{trans('projectlang.product')}}</label>
                                <select id="product_id" name="product_id" class="form-control">
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="collection_amount" class="control-label">{{trans('projectlang.collection_amount')}}</label>
                                <input type="text" class="form-control" id="collection_amount" name="collection_amount"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       onkeypress="restrictMinus(event);"
                                >
                            </div>
                        </div>


                        {{-- 3 --}}
                        <div class="row mb-2">

                            <div class="col-sm-4">
                                <label for="commission_amount" class="control-label">{{trans('projectlang.commission_amount')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" class="form-control" id="commission_rate" name="commission_rate" oninput="commissionAmount()" onkeypress="restrictMinus(event);" title="{{trans('projectlang.commission_rate_title')}}" required>
                                    <input type="text" class="form-control w-75" id="commission_amount" name="commission_amount" readonly
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="discount" class="control-label">{{trans('projectlang.discount')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" class="form-control" id="discount_rate" name="discount_rate" oninput="discountAmount()" onkeypress="restrictMinus(event);" required title="{{trans('projectlang.discount_rate_title')}}">
                                    <input type="text" class="form-control w-75" id="discount" name="discount"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           value=0 readonly>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="rate_vat" class="control-label">{{trans('projectlang.value_add_tax')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" name="rate_vat" id="rate_vat" class="form-control" oninput="totalIncTax()" onkeypress="restrictMinus(event);" required>
                                    <input type="text" class="form-control w-75" id="value_vat" name="value_vat" readonly
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    >
                                </div>
                            </div>

                        </div>

                        {{-- 4 --}}
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <label for="sub_total" class="control-label">{{trans('projectlang.sub_total')}}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control w-75" id="sub_total" name="sub_total"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           value=0 readonly>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="total" class="control-label">{{trans('projectlang.total_include_tax')}}</label>
                                <input type="text" class="form-control" id="total" name="total" readonly>
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <label for="note">{{trans('projectlang.notes_ar')}}</label>
                                <textarea class="form-control" id="notes_ar" name="notes_ar" rows="3"></textarea>
                            </div>

                            <div class="col-sm-6">
                                <label for="note">{{trans('projectlang.notes_en')}}</label>
                                <textarea class="form-control" id="notes_en" name="notes_en" rows="3"></textarea>
                            </div>
                        </div>

                        <br>

                        <p class="text-danger">{{trans('projectlang.attachment_format')}}</p>
                        <h5 class="card-title">{{trans('projectlang.attachments')}}</h5>

                        <div class="col-sm-12 col-md-12">
                            <input id="input-id" type="file" name="files[]" class="file" multiple data-preview-file-type="text">
                        </div>

                        <br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">{{trans('projectlang.save_data')}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- row closed -->
@endsection

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
                // allowedFileTypes: ['image'],
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
            $('select[name="department_id"]').on('change', function () {
                var dep_id = $(this).val();
                if(dep_id) {
                    $.ajax({
                        url: "{{\Illuminate\Support\Facades\URL::to('departments')}}/"+dep_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('select[name="product_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="product_id"]').append(
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

    <!--Calculate the total include tax-->
    <script>
        function commissionAmount() {
            var collection_amount = parseFloat(document.getElementById('collection_amount').value);
            var commission_rate = parseFloat(document.getElementById('commission_rate').value);
            if(typeof collection_amount === 'undefined' || ! collection_amount) {
                alert("{{trans('projectlang.collection_amount_alert')}}");
            } else {
                var commission_amount = collection_amount * commission_rate / 100;
                if(! isNaN(commission_amount)){
                    document.getElementById('commission_amount').value = commission_amount.toFixed(2);
                } else {
                    document.getElementById('commission_amount').value = 0;
                }
            }
        }

        function discountAmount() {
            var commission_amount = parseFloat(document.getElementById('commission_amount').value);
            var discount_rate = parseFloat(document.getElementById('discount_rate').value);
            if(typeof commission_amount === 'undefined' || ! commission_amount) {
                alert("{{trans('projectlang.commission_rate_alert')}}");
            } else {
                var discount_amount = commission_amount * discount_rate / 100;
                if(! isNaN(discount_amount)){
                    document.getElementById('discount').value = discount_amount.toFixed(2);
                } else {
                    document.getElementById('discount').value = 0;
                }

            }
        }



        function totalIncTax() {
            var commission_amount = parseFloat(document.getElementById('commission_amount').value);
            var discount = parseFloat(document.getElementById('discount').value);
            var add_tax_rate = parseFloat(document.getElementById('rate_vat').value);
            var net_commission = commission_amount - discount;

            if(typeof commission_amount === 'undefined' || ! commission_amount) {
                alert("{{trans('projectlang.commission_rate_alert')}}");
            } else {
                var commission_value_add_tax = net_commission * add_tax_rate / 100;
                var net_commission_include_tax = parseFloat(net_commission + commission_value_add_tax);

                if(! isNaN(commission_value_add_tax) || ! isNaN(net_commission_include_tax)){
                    document.getElementById('sub_total').value = net_commission.toFixed(2);
                    document.getElementById('value_vat').value = commission_value_add_tax.toFixed(2);
                    document.getElementById('total').value = net_commission_include_tax.toFixed(2);
                } else {
                    document.getElementById('sub_total').value = 0;
                    document.getElementById('value_vat').value = 0;
                    document.getElementById('total').value = 0;
                }
            }

        }

        //apply functions on update collection amount value
        const collection_amount_update = document.getElementById('collection_amount');
        const commission_rate_update = document.getElementById('commission_rate');
        const commission_amount_update = document.getElementById('commission_amount');
        const discount_rate_update = document.getElementById('discount_rate');
        const rate_vat_update = document.getElementById('rate_vat');

        collection_amount_update.addEventListener('change', updateValue);
        commission_rate_update.addEventListener('change', updateValue);
        discount_rate_update.addEventListener('change', updateValue);
        rate_vat_update.addEventListener('change', updateValue);

        function updateValue(e) {
            commission_amount_update.value = e.target.javascript(commissionAmount(), discountAmount(), totalIncTax())
        }
    </script>

    <!--Prevent negative input-->
    <script>
        function restrictMinus(e) {
            const inputKeyCode = e.keyCode ? e.keyCode : e.which;
            if (inputKeyCode != null) {
                if (inputKeyCode == 45) e.preventDefault();
            }
        }
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
