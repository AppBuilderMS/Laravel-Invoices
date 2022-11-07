@extends(backpack_view('blank'))
@section('content')

@if(\App\Models\Department::all()->count() < 1 || \App\Models\Product::all()->count() < 1)
    <div class="alert alert-info text-center">

        <h5><i class="fa fa-info-circle"></i> {{trans('projectlang.dashboard_hint')}}</h5>
    </div>
@endif

<div name="widget_976637818" section="before_content" class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-value">{{totalInvoicesSum()}} $</div>
                    <div>
                        <div class="text-value d-inline">{{allInvoicesCount()}}</div>
                        @if(app()->getLocale() === 'en')
                        <small class="d-inline">{{allInvoicesCount() <= 1 ? trans('projectlang.invoice') : trans('projectlang.invoicesCount')}}</small>
                        @else
                            <small class="d-inline">{{trans('projectlang.invoice')}}</small>
                        @endif
                    </div>
                </div>

                <div>{{trans('projectlang.total_invoices')}}</div>

                <div class="progress progress-white progress-xs my-2">
                    <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <small class="text-muted">{{trans('projectlang.total_invoices_percentage')}}</small>
                <strong>100%</strong>
            </div>

        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-value">{{invoicesSum(1)}} $</div>
                    <div>
                        <div class="text-value d-inline">{{invoicesCount(1)}}</div>
                        @if(app()->getLocale() === 'en')
                            <small class="d-inline">{{invoicesCount(1) <= 1? trans('projectlang.invoice') : trans('projectlang.invoicesCount')}}</small>
                        @else
                            <small class="d-inline">{{trans('projectlang.invoice')}}</small>
                        @endif
                    </div>
                </div>

                <div>{{trans('projectlang.totally_paid_invoices')}}</div>

                <div class="progress progress-white progress-xs my-2">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{invoicesPercentage(1)}}%"
                         aria-valuenow="{{invoicesPercentage(1)}}%"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <small class="text-muted">{{trans('projectlang.paid_invoices_percentage')}}</small>
                <strong>{{invoicesPercentage(1)}}%</strong>
            </div>

        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-value">{{invoicesSum(2)}} $</div>
                    <div>
                        <div class="text-value d-inline">{{invoicesCount(2)}}</div>
                        @if(app()->getLocale() === 'en')
                            <small class="d-inline">{{invoicesCount(2) <= 1 ? trans('projectlang.invoice') : trans('projectlang.invoicesCount')}}</small>
                        @else
                            <small class="d-inline">{{trans('projectlang.invoice')}}</small>
                        @endif
                    </div>
                </div>

                <div>{{trans('projectlang.partially_paid_invoices')}}</div>

                <div class="progress progress-white progress-xs my-2">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{invoicesPercentage(2)}}%"
                         aria-valuenow="{{invoicesPercentage(2)}}%"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <small class="text-muted">{{trans('projectlang.partially_paid_invoices_percentage')}}</small>
                <strong>{{invoicesPercentage(2)}}%</strong>
            </div>

        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="text-value">{{invoicesSum(3)}} $</div>
                    <div>
                        <div class="text-value d-inline">{{invoicesCount(3)}}</div>
                        @if(app()->getLocale() === 'en')
                            <small class="d-inline">{{invoicesCount(3) <= 1 ? trans('projectlang.invoice') : trans('projectlang.invoicesCount')}}</small>
                        @else
                            <small class="d-inline">{{trans('projectlang.invoice')}}</small>
                        @endif
                    </div>
                </div>

                <div>{{trans('projectlang.unpaid_invoices')}}</div>

                <div class="progress progress-white progress-xs my-2">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{invoicesPercentage(3)}}%"
                         aria-valuenow="{{invoicesPercentage(3)}}%"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <small class="text-muted">{{trans('projectlang.unpaid_invoices_percentage')}}</small>
                <strong>{{invoicesPercentage(3)}}%</strong>
            </div>

        </div>
    </div>

</div>

<div class="row" name="widget_573936319" section="before_content">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{trans('projectlang.chart_past_30days')}}</div>
            <div class="card-body">
                <div class="card-wrapper">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{trans('projectlang.dep_inv_stats')}}</div>
            <div class="card-body">
                <div class="card-wrapper">
                    {!! $chart2->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart->script() !!}
{!! $chart2->script() !!}
@endpush
