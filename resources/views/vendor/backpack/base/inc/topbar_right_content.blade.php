<!-- This file is used to store topbar (right) items -->
@if(authUserRole(['SuperAdmin', 'Admin']))
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="la la-bell"></i>
        <span class="badge badge-pill badge-danger">{{ backpack_auth()->user()->unreadNotifications->count() }}</span>
    </a>
    <div class="dropdown-menu  notification_container {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right'}} mr-4 pt-0">
        <div class="card">

            <div class="card-header" style="background-image: url('{{asset('/custome/images/notifications_bg.jpg')}}')">
                <div class="d-flex flex-column rounded-top notification_head">
                    <!--begin::Title-->
                    <h5 class="d-flex justify-content-center rounded-top">
                        <span class="text-white" style="line-height: 30px">{{trans('projectlang.user_notifications')}}</span>
                        <span class="header-btn btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ backpack_auth()->user()->unreadNotifications->count() }} {{trans('projectlang.new')}} </span>
                    </h5>
                    <!--end::Title-->
                    <a href="{{route('markAllAsRead')}}" class="btn btn-outline-warning btn-sm" style="width: fit-content; margin: auto; margin-top: 5px !important;">{{trans('projectlang.marked_as_read_all')}}</a>
                </div>
            </div>

            <div class="card-body">

                @foreach(backpack_auth()->user()->unreadNotifications as $notification)
                    @if($notification->type === 'App\Notifications\AddInvoice')
                        <a href="{{route('markOneAsRead', $notification->data['data'])}}" class="nav-item">
                            <div class="nav-link d-flex">
                                <div class="nav-icon success-bg">
                                    <i class="la la-plus-square-o text-success"></i>
                                </div>
                                <div class="nav-text">
                                    <div class="font-weight-bold">
                                        {{ app()->getLocale() === 'ar' ? $notification->data['title_ar'] : $notification->data['title_en'] }} {{ $notification->data['user'] }}
                                    </div>
                                    <div class="text-muted notification_time">{{$notification->created_at->diffForHumans()}}</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($notification->type === 'App\Notifications\EditInvoice')
                        <a href="{{route('markOneEditedAsRead', $notification->data['data_edited'])}}" class="nav-item">
                            <div class="nav-link d-flex">
                                <div class="nav-icon info-bg">
                                    <i class="la la-edit text-info"></i>
                                </div>
                                <div class="nav-text">
                                    <div class="font-weight-bold">
                                        {{ app()->getLocale() === 'ar' ? $notification->data['title_ar'] : $notification->data['title_en'] }} {{ $notification->data['user'] }}
                                    </div>
                                    <div class="text-muted notification_time">{{$notification->created_at->diffForHumans()}}</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($notification->type === 'App\Notifications\ArchiveInvoice')
                        <a href="{{route('markOneArchivedAsRead', $notification->data['data_archived'])}}" class="nav-item">
                            <div class="nav-link d-flex">
                                <div class="nav-icon warning-bg">
                                    <i class="la la-archive text-warning"></i>
                                </div>
                                <div class="nav-text">
                                    <div class="font-weight-bold">
                                        {{ app()->getLocale() === 'ar' ? $notification->data['title_ar'] : $notification->data['title_en'] }} {{ $notification->data['user'] }}
                                    </div>
                                    <div class="text-muted notification_time">{{$notification->created_at->diffForHumans()}}</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($notification->type === 'App\Notifications\DeleteInvoice')
                        <a href="{{route('markOneDeletedAsRead', $notification->data['data_deleted'])}}" class="nav-item">
                            <div class="nav-link d-flex">
                                <div class="nav-icon danger-bg">
                                    <i class="la la-trash-alt text-danger"></i>
                                </div>
                                <div class="nav-text">
                                    <div class="font-weight-bold">
                                        {{ app()->getLocale() === 'ar' ? $notification->data['title_ar'] : $notification->data['title_en'] }} {{ $notification->data['user'] }}
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="text-muted notification_time">{{$notification->created_at->diffForHumans()}} </div>
                                        <span class="d-block text-danger">#{{$notification->data['invoice_number']}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach

            </div>

        </div>
    </div>
</li>
@endif


<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-list"></i></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-map"></i></a></li>


<li class="nav-item dropdown pr-3">
    <a class="nav-link lang" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="las la-language"></i>
    </a>
    <div class="dropdown-menu {{LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right'}} mr-4 pb-1 pt-1">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a class="dropdown-item" rel="alternate" hreflang="{{$localeCode}}" href="{{LaravelLocalization::getLocalizedURL($localeCode, null, [], true)}}">
                <i class="las la-flag"></i>
                {{ $properties['native'] }}
            </a>
            @if(! $loop->last)
                <div class="dropdown-divider"></div>
            @endif
        @endforeach
    </div>
</li>


