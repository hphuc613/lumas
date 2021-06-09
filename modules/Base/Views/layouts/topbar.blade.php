<?php

use App\AppHelpers\Helper;
use Modules\Base\Model\Status;

$logo          = Helper::getSetting('LOGO');
$notifications = Auth::user()->notifications->sortByDesc('updated_at')->toArray();
$notification_unread = 0;
foreach (Auth::user()->unreadNotifications as $unread) {
    $data = $unread['data'];
    if ($data['status'] == Status::STATUS_ACTIVE) {
        $notification_unread++;
    }
}
?>
<!-- Logo -->
<div id="logo" class="logo d-flex justify-content-between">
    <img src="{{ asset(!empty($logo) ? $logo : '/logo/logo.png') }}" alt="logo">
    <button id="menu-button" class="btn border-0 menu-button">
        <i class="fas fa-bars"></i>
    </button>
</div>


<!-- Right-Sidebar -->
<div class="d-flex align-items-center pl-2">
    <div class="mr-2" style="width: 160px;">
        <select class="change-language form-control" id="change-language" data-href="{{ route('change_locale','') }}">
            <option value="en" @if(session()->get('locale') === 'en') selected @endif>{{ trans('English') }}(US)
            </option>
            <option value="cn" @if(session()->get('locale') === 'cn') selected @endif>{{ trans('Chinese') }}
                (Traditional)
            </option>
        </select>
    </div>
    <div class="notification-belling" data-toggle="collapse" href="#notification-list" aria-expanded="false">
        <a href="#" class="text-light">
            <i class="fas fa-bell position-relative">
                @if($notification_unread > 0)
                    <span
                        class="notification-num">{{ ($notification_unread <= 9) ? $notification_unread : '9+' }}</span>
                @endif
            </i>
        </a>
        <div class="collapse" id="notification-list">
            <div class="card border-0">
                <div class="card-body p-2 ">
                    <h4>{{ trans('Notifications') }}</h4>
                    <div class="notify">
                        <div id="new-notification">
                            <h5>{{ trans('New') }}</h5>
                            <ul class="notification-list list-unstyled">
                                {!! notificationList($notifications, 1) !!}
                            </ul>
                        </div>
                        <div id="before-notification">
                            <h5>{{ trans('Before') }}</h5>
                            <ul class="notification-list list-unstyled">
                                {!! notificationList($notifications, 0) !!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-sidebar float-right" data-toggle="collapse" href="#list-menu" aria-expanded="false">
        <a href="#" class="text-light">{{ \Illuminate\Support\Facades\Auth::user()->name ?? null }}</a>
        <ul class="collapse list-unstyled border menu-sidebar" id="list-menu">
            <li><a href="{{ route('get.profile.update') }}"> {{ trans('Profile') }}</a></li>
            <li><a href="{{ route('get.logout.admin') }}"> {{ trans('Log out') }}</a></li>
        </ul>
    </div>
</div>
