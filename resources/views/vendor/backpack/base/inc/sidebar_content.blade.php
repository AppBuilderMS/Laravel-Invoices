<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{route('dashboard')}}">
        <i class="nav-icon la la-dashboard"></i>
        {{ trans('backpack::base.dashboard') }}
    </a>
</li>

@if(authUserPermission('Invoices'))
<li class="nav-title">{{ trans('projectlang.invoices') }}</li>
<li class="nav-item nav-dropdown"> <!--class open-->
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-file-invoice"></i> {{trans('projectlang.invoices')}}</a>
    <ul class="nav-dropdown-items">
        @if(authUserPermission('Invoices list'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('invoices.index')}}">
                    <i class="nav-icon la la-list"></i>
                    <span>{{trans('projectlang.invoices-list')}}</span>
                </a>
            </li>
        @endif
        @if(authUserPermission('Unpaid invoices'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('unpaidInvoices')}}">
                    <i class="nav-icon la la-skull-crossbones"></i>
                    <span>{{trans('projectlang.non-paid')}}</span>
                </a>
            </li>
        @endif
        @if(authUserPermission('Partially paid invoices'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('partiallyPaidInvoices')}}">
                    <i class="nav-icon la la-check"></i>
                    <span>{{trans('projectlang.partialy-paid')}}</span>
                </a>
            </li>
        @endif
        @if(authUserPermission('Paid invoices'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('paidInvoices')}}">
                    <i class="nav-icon la la-check-double"></i>
                    <span>{{trans('projectlang.paid-invoices')}}</span>
                </a>
            </li>
        @endif
    </ul>
</li>
@endif

@if(authUserPermission('Invoices archive'))
<li class="nav-title">{{ trans('projectlang.archived_invoices') }}</li>
<li class="nav-item nav-dropdown"> <!--class open-->
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-archive"></i> {{trans('projectlang.archived_invoices')}}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a class="nav-link" href="{{route('archivedInvoices.index')}}">
                <i class="nav-icon la la-list"></i>
                <span>{{trans('projectlang.archived_invoices_list')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('unpaidArchivedInvoices')}}">
                <i class="nav-icon la la-skull-crossbones"></i>
                <span>{{trans('projectlang.non-paid')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('partiallyPaidArchivedInvoices')}}">
                <i class="nav-icon la la-check"></i>
                <span>{{trans('projectlang.partialy-paid')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('paidArchivedInvoices')}}">
                <i class="nav-icon la la-check-double"></i>
                <span>{{trans('projectlang.paid-invoices')}}</span>
            </a>
        </li>
    </ul>
</li>
@endif

@if(authUserPermission('Reports'))
<li class="nav-title">{{ trans('projectlang.reports') }}</li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-newspaper"></i>{{trans('projectlang.reports')}}</a>
    <ul class="nav-dropdown-items">
    @if(authUserPermission('Invoices report'))
        <li class="nav-item"><a class="nav-link" href="{{route('invoicesReports')}}"><i class="nav-icon la la-list"></i><span>{{trans('projectlang.invoices-report')}}</span></a></li>
    @endif
    @if(authUserPermission('Customers report'))
        <li class="nav-item"><a class="nav-link" href="{{route('customersReports')}}"><i class="nav-icon la la-group"></i> <span>{{trans('projectlang.customers-report')}}</span></a></li>
    @endif
    </ul>
</li>
@endif

@if(authUserPermission('Users'))
<li class="nav-title">{{ trans('projectlang.users') }}</li>
<li class="nav-item nav-dropdown"> <!--class open-->
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users-cog"></i>{{trans('projectlang.users')}}</a>
    <ul class="nav-dropdown-items">
        @if(authUserPermission('Users list'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('users.index')}}">
                    <i class="nav-icon la la-list"></i>
                    <span>{{trans('projectlang.users-list')}}</span>
                </a>
            </li>
        @endif

        @if(authUserPermission('Users roles'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('roles.index')}}">
                    <i class="nav-icon la la-users"></i>
                    <span>{{trans('projectlang.users-roles')}}</span>
                </a>
            </li>
        @endif

        @if(authUserPermission('Users permissions'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('permissions.index')}}">
                    <i class="nav-icon la la-key"></i>
                    <span>{{trans('projectlang.users-permisions')}}</span>
                </a>
            </li>
        @endif
    </ul>
</li>
@endif

@if(authUserPermission('settings'))
<li class="nav-title">{{ trans('projectlang.settings') }}</li>
<li class="nav-item nav-dropdown"> <!--class open-->
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i>{{trans('projectlang.settings')}}</a>
    <ul class="nav-dropdown-items">
        @if(authUserPermission('Departments'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('departments.index')}}">
                    <i class="nav-icon la la-sitemap"></i>
                    <span>{{trans('projectlang.departments')}}</span>
                </a>
            </li>
        @endif
        @if(authUserPermission('Products'))
            <li class="nav-item">
                <a class="nav-link" href="{{route('products.index')}}">
                    <i class="nav-icon la la-boxes"></i>
                    <span>{{trans('projectlang.products')}}</span>
                </a>
            </li>
        @endif
    </ul>
</li>
@endif

