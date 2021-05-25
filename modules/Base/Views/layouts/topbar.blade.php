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
    <div class="right-sidebar float-right" data-toggle="collapse" href="#list-menu" aria-expanded="false">
        <a href="#" class="text-light">{{ \Illuminate\Support\Facades\Auth::user()->name ?? null }}</a>
        <ul class="collapse list-unstyled border menu-sidebar" id="list-menu">
            <li><a href="{{ route('get.profile.update') }}"> {{ trans('Profile') }}</a></li>
            <li><a href="{{ route('get.logout.admin') }}"> {{ trans('Log out') }}</a></li>
        </ul>
    </div>
</div>
