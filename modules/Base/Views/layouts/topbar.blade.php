<?php

use App\AppHelpers\Helper;
use Modules\Base\Model\Status;

$logo                = Helper::getSetting('LOGO');
$notifications       = Auth::user()->notifications->sortByDesc('updated_at')->toArray();
$notification_unread = 0;
foreach(Auth::user()->unreadNotifications as $unread){
    $data = $unread['data'];
    if($data['status'] == Status::STATUS_ACTIVE){
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
    @include('Base::layouts._notification')
    <div class="right-sidebar float-right">
        <a class="text-light" data-toggle="collapse"
           href="#list-menu">{{ \Illuminate\Support\Facades\Auth::user()->name ?? null }}</a>
        <ul class="collapse list-unstyled menu-sidebar" id="list-menu">
            <li><a href="{{ route('get.profile.update') }}"> {{ trans('Profile') }}</a></li>
            <li class="collapse-group">
                <a href="#languages" data-toggle="collapse">{{ trans('Languages') }}</a>
                <ul class="collapse list-unstyled" id="languages" style="right: 138px; width: 200px;">
                    @if(session()->get('locale') === 'cn')
                        <li>
                            <a href="{{ route('change_locale','en') }}">
                                {{ trans('English') }}(US)
                            </a>
                        </li>
                        <li>
                            <a class="text-success" href="{{ route('change_locale','cn') }}">
                                <i class="fas fa-check"></i>
                                {{ trans('Chinese') }}(Traditional)
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="text-success" href="{{ route('change_locale','en') }}">
                                <i class="fas fa-check"></i>
                                {{ trans('English') }}(US)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('change_locale','cn') }}">
                                {{ trans('Chinese') }}(Traditional)
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li><a href="{{ route('get.logout.admin') }}"> {{ trans('Log out') }}</a></li>
        </ul>
    </div>
</div>
