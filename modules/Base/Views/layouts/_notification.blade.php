<div class="notification-belling" data-toggle="collapse" href="#notification-list" aria-expanded="false">
    <a href="#" class="text-light">
        <i class="fas fa-bell position-relative">
            @if($notification_unread > 0)
                <span class="notification-num">{{ ($notification_unread <= 9) ? $notification_unread : '9+' }}</span>
            @endif
        </i>
    </a>
    <div class="collapse" id="notification-list">
        <div class="card border-0">
            <div class="card-body pl-2 pr-2">
                <h4 class="">{{ trans('Notifications') }}</h4>
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