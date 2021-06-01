@php
    use App\AppHelpers\Helper;
    $logo = Helper::getSetting('LOGO')
@endphp

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
            <i class="fas fa-bell"></i>
            <span class="number"></span>
        </a>
        <div class="collapse border" id="notification-list">
            <div class="card">
                <div class="card-body p-2">
                    <h4>{{ trans('Notifications') }}</h4>
                    <div id="new-notification">
                        <h5>{{ trans('New') }}</h5>
                        <ul class="list-unstyled">
                            @php($notifications =  array_slice(Auth::user()->notifications->toArray(), 0, 3))
                            @foreach ($notifications as $notification)
                                @php($data =  $notification['data'])

                                <li>
                                    <a class="dropdown-item" href="">
                                        <span>{{ $data['title'] }}</span><br>
                                        <small class="timestamp">
                                            About {{ calculateTimeNotification($notification['created_at']) }} ago
                                        </small>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div id="before-notification">
                        <h5>{{ trans('Before') }}</h5>
                        <ul class="list-unstyled">
                            @php($notifications =  array_slice(Auth::user()->notifications->toArray(), 3))
                            @foreach ($notifications as $notification)
                                @php($data =  $notification['data'])
                                <li>
                                    <a class="dropdown-item" href="">
                                        <span>{{ $data['title'] }}</span><br>
                                        @php($minutes = time()-strtotime($notification['created_at']))
                                        <small class="timestamp">
                                            About {{ calculateTimeNotification($notification['created_at']) }} ago
                                        </small>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
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
