@extends(backpack_view('blank'))
@push('after_styles')
    <style>
        .invoice {
            background: #fff;
            padding: 20px;
            box-shadow: 0 15px 30px 0 rgb(0 0 0 / 11%), 0 5px 15px 0 rgb(0 0 0 / 8%);
            margin-bottom: 50px;
        }
        html[dir=ltr] .invoice {
            font-size: 14px;
        }
        .invoice-company {
            font-size: 20px
        }
        .invoice-header {
            /*margin: 0 -20px;*/
            background: #f0f3f4;
            padding: 20px
        }
        .invoice-from,
        .invoice-to{
            margin-top: 7%;
        }
        .invoice-from strong,
        .invoice-to strong {
            font-size: 16px;
            font-weight: 600;
        }
        .invoice-date table{
            box-shadow: 0 15px 30px 0 rgb(0 0 0 / 11%), 0 5px 15px 0 rgb(0 0 0 / 8%);
        }

        .invoice-price {
            background: #f0f3f4;
            display: table;
            width: 100%
        }

        .invoice-price .invoice-price-left,
        .invoice-price .invoice-price-right {
            display: table-cell;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            width: 75%;
            position: relative;
            vertical-align: middle
        }

        .invoice-price .invoice-price-left .sub-price {
            display: table-cell;
            vertical-align: middle;
            padding: 0 20px
        }

        .invoice-price small {
            font-size: 12px;
            font-weight: 400;
            display: block
        }

        .invoice-price .invoice-price-row {
            display: table;
            float: left
        }
        html[dir=rtl] .invoice-price .invoice-price-row {
            display: table;
            float: right
        }
        .invoice-price .invoice-price-right {
            width: 25%;
            background: #2d353c;
            color: #fff;
            font-size: 28px;
            text-align: right;
            vertical-align: bottom;
            font-weight: 300
        }
        html[dir=rtl] .invoice-price .invoice-price-right {
            text-align: left;
        }
        .invoice-price .invoice-price-right small {
            display: block;
            opacity: .6;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px
        }
        html[dir=rtl] .invoice-price-right small {
            left: 130px;
        }
        .invoice-footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px
        }
        .invoice-note {
            color: #999;
            margin-top: 80px;
            font-size: 85%
        }

        .invoice>div:not(.invoice-footer) {
            margin-bottom: 20px
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="invoice">
                    <!-- begin invoice-company -->
                    <div class="invoice-company text-inverse f-w-600 d-flex justify-content-between">
                        <span class="d-print-none">
                            <a href="javascript:" onclick="window.print()" class="btn btn-sm btn-danger" ><i class="fa fa-file-pdf t-plus-1 fa-fw fa-lg"></i> {{trans('projectlang.print_pdf')}}</a>
                        </span>
                        <span>Company Name, Inc</span>
                    </div>
                    <!-- end invoice-company -->
                    <!-- begin invoice-header -->
                    <div class="invoice-header" id="content">
                        <h3 class="position-absolute font-weight-bold mt-3">{{trans('projectlang.collection_invoice')}}</h3>
                        <div class="row">
                            <div class="col-md-3 invoice-from">
                                <small>{{trans('projectlang.from')}}</small>
                                <address>
                                    <strong class="text-inverse">{{trans('projectlang.company_name')}}</strong><br>
                                    Street Address<br>
                                    City, Zip Code<br>
                                    Phone: (123) 456-7890<br>
                                    Fax: (123) 456-7890
                                </address>
                            </div>
                            <div class="col-md-3  invoice-to">
                                <small>{{trans('projectlang.to')}}</small>
                                <address>
                                    <strong class="text-inverse">{{$lang == 'ar' ? $invoice->department->dep_name_ar : $invoice->department->dep_name_en}}</strong><br>
                                    Street Address<br>
                                    City, Zip Code<br>
                                    Phone: (123) 456-7890<br>
                                    Fax: (123) 456-7890
                                </address>
                            </div>
                            <div class="col-md-6 invoice-date mt-3">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="border-top-0 pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.invoice_number')}}</th>
                                            <td class="border-top-0 pr-3 pl-3 font-weight-bold" scope="row">{{$invoice->invoice_number}}</td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.invoice_date')}}</th>
                                            <td class="pr-3 pl-3">{{$invoice->invoice_date}}</td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.due_date')}}</th>
                                            <td class="pr-3 pl-3">{{$invoice->due_date}}</td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.product')}}</th>
                                            <td class="pr-3 pl-3">{{$lang == 'ar' ? $invoice->product->product_name_ar : $invoice->product->product_name_en}}</td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.collection_amount')}}</th>
                                            <td class="pr-3 pl-3">{{number_format($invoice->collection_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.status')}}</th>
                                            <td class="pr-3 pl-3">{{$lang == 'ar' ? $invoice->status_ar : $invoice->status_en}}</td>
                                        </tr>

                                        <tr>
                                            @if($invoice->value_status == '2')
                                                <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.partial_payment_date')}}</th>
                                                <td class="pr-3 pl-3">{{$invoice->partial_payment_date}}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($invoice->partial_payment_date and $invoice->value_status == '1')
                                                <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.partial_payment_date')}}</th>
                                                <td class="pr-3 pl-3">{{$invoice->partial_payment_date}}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($invoice->value_status == '1')
                                                <th class="pr-3 pl-3 text-uppercase" scope="col">{{trans('projectlang.total_payment_date')}}</th>
                                                <td class="pr-3 pl-3">{{$invoice->total_payment_date}}</td>
                                            @endif
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- end invoice-header -->
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        <!-- begin table-responsive -->
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th class="text-uppercase">{{trans('projectlang.task_description')}}</th>
                                    <th class="text-center" width="20%">{{trans('projectlang.rate')}}</th>
                                    <th class="text-right" width="20%">{{trans('projectlang.value')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <span class="text-inverse">{{trans('projectlang.commission_amount')}}</span><br>
                                    </td>
                                    <td class="text-center">{{$invoice->commission_rate}}</td>
                                    <td class="text-right">{{number_format($invoice->commission_amount)}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-inverse">{{trans('projectlang.discount')}}</span><br>
                                    </td>
                                    <td class="text-center">{{$invoice->discount_rate}}</td>
                                    <td class="text-right">({{number_format($invoice->discount)}})</td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-inverse">{{trans('projectlang.sub_total')}}</span><br>
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-right">{{number_format($invoice->sub_total)}}</td>
                                </tr>

                                <tr>
                                    <td>
                                        <span class="text-inverse">{{trans('projectlang.value_add_tax')}}</span><br>
                                    </td>
                                    <td class="text-center">{{$invoice->rate_vat}}</td>
                                    <td class="text-right">{{number_format($invoice->value_vat)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- begin invoice-price -->
                        <div class="invoice-price">
                            <div class="invoice-price-left">
                                <div class="invoice-price-row">
                                    <div class="sub-price">
                                        <small>{{trans('projectlang.sub_total')}}</small>
                                        <span class="text-inverse">{{number_format($invoice->sub_total)}}</span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-plus text-muted"></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>{{trans('projectlang.value_add_tax')}}</small>
                                        <span class="text-inverse">{{number_format($invoice->value_vat)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-price-right">
                                <small>{{trans('projectlang.total_include_tax')}}</small> <span class="f-w-600">{{number_format($invoice->total)}}</span>
                            </div>
                        </div>
                        <!-- end invoice-price -->

                        <!--Start Partial payments details -->
                        @if($invoice->value_status == '2' or $invoice->partial_payment_date)
                            <div class="table-responsive mt-5">
                                <table class="table table-invoice">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase">{{trans('projectlang.task_description')}}</th>
                                            <th class="text-center" width="20%">{{trans('projectlang.partial_payment_date')}}</th>
                                            <th class="text-right" width="20%">{{trans('projectlang.payment_amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span class="text-inverse">{{trans('projectlang.invoice_partially_paid')}}</span><br>
                                        </td>
                                        <td class="text-center">{{$invoice->partial_payment_date}}</td>
                                        <td class="text-right">{{number_format($invoice->payment_amount)}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- begin remaining amount -->
                            <div class="invoice-price">
                                <div class="invoice-price-left">
                                    <div class="invoice-price-row">
                                        <div class="sub-price">
                                            <small>{{trans('projectlang.total_invoice')}}</small>
                                            <span class="text-inverse">{{number_format($invoice->total)}}</span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price">
                                            <small>{{trans('projectlang.payment_amount')}}</small>
                                            <span class="text-inverse">{{number_format($invoice->payment_amount)}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-price-right">
                                    <small>{{trans('projectlang.remaining_amount')}}</small> <span class="f-w-600">{{number_format($invoice->remaining_amount)}}</span>
                                </div>
                            </div>
                            <!-- end remaining amount -->
                        @endif
                        <!--end Partial payments details -->

                    </div>
                    <!-- end invoice-content -->
                    <!-- begin invoice-note -->
                    <div class="invoice-note">
                        * {{trans('projectlang.invoice_note_1')}} [{{trans('projectlang.company_name')}}]<br>
                        * {{trans('projectlang.invoice_note_2')}}<br>
                        * {{trans('projectlang.invoice_note_3')}}  [{{trans('projectlang.name')}}, {{trans('projectlang.phone_number')}},
                        {{trans('projectlang.email')}}]
                    </div>
                    <!-- end invoice-note -->
                    <!-- begin invoice-footer -->
                    <div class="invoice-footer">
                        <p class="text-center text-uppercase m-b-5 f-w-600">
                            {{trans('projectlang.invoice_footer')}}
                        </p>
                        <p class="text-center" dir="ltr">
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> matiasgallipoli.com</span>
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:016-18192302</span>
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> rtiemps@gmail.com</span>
                        </p>
                    </div>
                    <!-- end invoice-footer -->
                </div>
            </div>
        </div>
    </div>
@endsection
