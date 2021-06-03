<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Http\Controllers\BaseController;


class NotificationController extends BaseController
{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function readNotification($id) {
        $notification = Auth::user()->notifications->where('id', $id)->first();

        if (!empty($notification)) {
            if (empty($notification->read_at)) {
                $notification->markAsRead();
            }
            $appointment = $notification['data'];

            return redirect()->route('get.member.appointment',
                [$appointment['member_id'], 'type' => $appointment['type']]);
        }

        return redirect()->back();
    }
}
