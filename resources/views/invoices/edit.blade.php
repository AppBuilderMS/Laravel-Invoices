@extends(backpack_view('blank'))
@push('after_styles')
<!--Internal Datepicker css -->
<link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.css') }}" rel="stylesheet">
@endpush
@section('content')
    <h3 class="heading">{{$heading . ' ' .trans('projectlang.number') . ' ' . $invoice->invoice_number}}</h3>

    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PATCH')
                        {{-- 1 --}}
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label for="invoice_number" class="control-label">{{trans('projectlang.invoice_number')}}</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{$invoice->invoice_number}}" readonly>
                            </div>

                            <div class="col-sm-4">
                                <label for="invoice_Date" class="control-label">{{trans('projectlang.invoice_date')}}</label>
                                <input class="form-control" name="invoice_date" placeholder="YYYY-MM-DD" type="text" value="{{$invoice->invoice_date}}" readonly>
                            </div>

                            <div class="col-sm-4">
                                <label for="due_date" class="control-label">{{trans('projectlang.due_date')}}</label>
                                <input class="form-control datepicker @if($lang === 'ar') datepicker-ar @endif" id="due_date" name="due_date" placeholder="YYYY-MM-DD" type="text" value="{{$invoice->due_date}}" required>
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
                                        <option value="{{ $department->id }}" {{$department->id === $invoice->department_id ? 'selected' : ''}}> {{$lang === 'ar' ? $department->dep_name_ar :  $department->dep_name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="product_id" class="control-label">{{trans('projectlang.product')}}</label>
                                <select id="product_id" name="product_id" class="form-control">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{$product->id === $invoice->product_id ? 'selected' : ''}}> {{$lang === 'ar' ? $product->product_name_ar :  $product->product_name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="collection_amount" class="control-label">{{trans('projectlang.collection_amount')}}</label>
                                <input type="text" class="form-control" id="collection_amount" name="collection_amount"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       value="{{$invoice->collection_amount}}"
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
                                    <input type="text" class="form-control" id="commission_rate" name="commission_rate" oninput="commissionAmount()" onkeypress="restrictMinus(event);" title="{{trans('projectlang.commission_rate_title')}}" value="{{$invoice->commission_rate}}" required>
                                    <input type="text" class="form-control w-75" id="commission_amount" name="commission_amount" readonly
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           value="{{$invoice->commission_amount}}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="discount" class="control-label">{{trans('projectlang.discount')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" class="form-control" id="discount_rate" name="discount_rate" oninput="discountAmount()" onkeypress="restrictMinus(event);" required title="{{trans('projectlang.discount_rate_title')}}" value="{{$invoice->discount_rate}}">
                                    <input type="text" class="form-control w-75" id="discount" name="discount"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           value="{{$invoice->discount}}" readonly>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="rate_vat" class="control-label">{{trans('projectlang.value_add_tax')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" name="rate_vat" id="rate_vat" class="form-control" oninput="totalIncTax()" onkeypress="restrictMinus(event);" required value="{{$invoice->rate_vat}}">
                                    <input type="text" class="form-control w-75" id="value_vat" name="value_vat" readonly
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                           value="{{$invoice->value_vat}}"
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
                                           value="{{$invoice->sub_total}}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="total" class="control-label">{{trans('projectlang.total_include_tax')}}</label>
                                <input type="text" class="form-control" id="total" name="total" readonly value="{{$invoice->total}}">
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <label for="note">{{trans('projectlang.notes_ar')}}</label>
                                <textarea class="form-control" id="notes_ar" name="notes_ar" rows="3">{{$invoice->notes_ar}}</textarea>
                            </div>

                            <div class="col-sm-6">
                                <label for="note">{{trans('projectlang.notes_en')}}</label>
                                <textarea class="form-control" id="notes_en" name="notes_en" rows="3">{{$invoice->notes_en}}</textarea>
                            </div>
                        </div>

                        <br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">{{trans('projectlang.save_data')}}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.paymentUpdate') }}" method="post" autocomplete="off" >
                        @csrf

                            <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                            <input type="hidden" name="value_status" value="{{$invoice->value_status}}">

                        <div class="row mb-2">
                            {{-- 1 --}}
                            <div class="col-sm-6">
                                <label for="payment_amount" class="control-label">{{trans('projectlang.payment_amount')}}</label>
                                <input class="form-control" id="payment_amount" name="payment_amount" type="text" value="{{$invoice->payment_amount}}"  required oninput="paymentAmount()" onkeypress="restrictMinus(event);" @if($invoice->value_status == '2' or $invoice->value_status == '1')readonly @endif>
                            </div>

                            {{-- 2 --}}
                            <div class="col-sm-6">
                                <label for="partial_payment_date" class="control-label">{{trans('projectlang.partial_payment_date')}}</label>
                                <input class="form-control" id="partial_payment_date" name="partial_payment_date" value="{{$invoice->partial_payment_date}}" placeholder="YYYY-MM-DD" type="text" readonly @if($invoice->value_status == '2' or $invoice->value_status == '1')readonly @endif>
                            </div>
                        </div>

                        <div class="row mb-2">
                            {{-- 3 --}}
                            <div class="col-sm-6">
                                <label for="remaining_amount" class="control-label">{{trans('projectlang.remaining_amount')}}</label>
                                <input class="form-control" id="remaining_amount" name="remaining_amount" type="text" value="{{$invoice->remaining_amount}}"   required readonly>
                            </div>

                            {{-- 4 --}}
                            <div class="col-sm-6">
                                <label for="total_payment_date" class="control-label">{{trans('projectlang.total_payment_date')}}</label>
                                <input class="form-control" id="total_payment_date" name="total_payment_date" value="{{$invoice->value_status == '2' ? date('Y-m-d') :$invoice->total_payment_date}}" placeholder="YYYY-MM-DD" type="text" readonly @if($invoice->value_status == '1') disabled @endif>
                            </div>
                        </div>

                        @if(authUserPermission('Change payment status'))
                            <div class="row mb-2">
                                {{-- 1 --}}
                                <div class="col-sm-4">
                                    <label for="status" class="control-label">{{trans('projectlang.payment_status')}}</label>
                                    <select name="status" class="form-control">
                                        <option value="0" selected disabled>{{trans('projectlang.select_new_payment_status')}}</option><!--placeholder-->
                                        <option value="2" id="partially_paid_option" @if($invoice->value_status == '2') selected disabled @endif @if($invoice->value_status == '1') disabled @endif> {{trans('projectlang.partially_paid')}}</option>
                                        <option value="1" id="totally_paid_option" @if($invoice->value_status == '1') selected disabled @endif @if($invoice->value_status == '2') selected @endif> {{trans('projectlang.totally_paid')}}</option>
                                    </select>
                                </div>

                                <div class="col-sm-4 d-flex align-items-end">
                                    <button type="submit" @if($invoice->value_status == '1') style="display: none" @endif  class="btn btn-primary">{{trans('projectlang.update_payment_status')}}</button>
                                </div>
                            </div>
                        @endif


                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- row closed -->
@endsection
@push('after_scripts')
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
            if (typeof collection_amount === 'undefined' || !collection_amount) {
                alert("{{trans('projectlang.collection_amount_alert')}}");
            } else {
                var commission_amount = collection_amount * commission_rate / 100;
                if (!isNaN(commission_amount)) {
                    document.getElementById('commission_amount').value = commission_amount.toFixed(2);
                } else {
                    document.getElementById('commission_amount').value = 0;
                }
            }
        }

        function discountAmount() {
            var commission_amount = parseFloat(document.getElementById('commission_amount').value);
            var discount_rate = parseFloat(document.getElementById('discount_rate').value);
            if (typeof commission_amount === 'undefined' || !commission_amount) {
                alert("{{trans('projectlang.commission_rate_alert')}}");
            } else {
                var discount_amount = commission_amount * discount_rate / 100;
                if (!isNaN(discount_amount)) {
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

            if (typeof commission_amount === 'undefined' || !commission_amount) {
                alert("{{trans('projectlang.commission_rate_alert')}}");
            } else {
                var commission_value_add_tax = net_commission * add_tax_rate / 100;
                var net_commission_include_tax = parseFloat(net_commission + commission_value_add_tax);

                if (!isNaN(commission_value_add_tax) || !isNaN(net_commission_include_tax)) {
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
        const collection_amount = document.getElementById('collection_amount');
        const commission_amount = document.getElementById('commission_amount');
        const commission_rate = document.getElementById('commission_rate');
        const discount_rate = document.getElementById('discount_rate');

        collection_amount.addEventListener('input', updateValue);
        commission_rate.addEventListener('input', updateValue);
        discount_rate.addEventListener('input', updateValue);

        function updateValue(e) {
            commission_amount.value = e.target.javascript(commissionAmount(), discountAmount(), totalIncTax());
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

    <!--Calculate payments-->
    <script>
        function paymentAmount() {
            const total = parseFloat(document.getElementById('total').value);
            const payment_amount = parseFloat(document.getElementById('payment_amount').value);
            if (typeof payment_amount === 'undefined' || !payment_amount) {
                alert("{{trans('projectlang.collection_amount_alert')}}");
            } else {
                const remaining_amount = total - payment_amount;
                if (!isNaN(remaining_amount)) {
                    document.getElementById('remaining_amount').value = remaining_amount.toFixed(2);
                } else {
                    document.getElementById('remaining_amount').value = 0;
                }
            }

            <!--Validate payments inputs-->
            const remaining_amount = parseFloat(document.getElementById('remaining_amount').value);
            const remaining_amount_input = document.getElementById('remaining_amount');
            const payment_amount_input = document.getElementById('payment_amount');
            const partial_payment_date = document.getElementById('partial_payment_date');
            const partially_paid_option = document.getElementById('partially_paid_option');
            const totally_paid_option = document.getElementById('totally_paid_option');
            const total_payment_date = document.getElementById('total_payment_date');

            if(remaining_amount > 0) {
                var date = new Date();
                var year = date.getFullYear();
                var month = date.getMonth()+1;
                var dt = date.getDate();
                if (dt < 10) {
                     dt = '0' + dt;
                }
                if (month < 10) {
                    month = '0' + month;
                }
                partial_payment_date.value = year+'-' + month + '-'+dt;
                partially_paid_option.selected = true;
                totally_paid_option.disabled = true;
                total_payment_date.value = '';
            }

            if(remaining_amount < 0) {
                alert("{{trans('projectlang.negative_results')}}");
                remaining_amount_input.value = "";
                payment_amount_input.value = "";
            }

            if(remaining_amount == 0) {
                partially_paid_option.disabled = true;
                totally_paid_option.selected = true;
                totally_paid_option.disabled = false;
                partial_payment_date.value = '';

                var date = new Date();
                var year = date.getFullYear();
                var month = date.getMonth()+1;
                var dt = date.getDate();
                if (dt < 10) {
                    dt = '0' + dt;
                }
                if (month < 10) {
                    month = '0' + month;
                }
                total_payment_date.value = year+'-' + month + '-'+dt;

            }
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


@endpush
