<?php

namespace Modules\Base\Console;

use App\AppHelpers\Helper;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Modules\Base\Model\Status;
use Modules\Setting\Model\AppointmentSetting;
use Modules\Setting\Model\Setting;
use Modules\User\Model\User;
use Pusher\ApiErrorException;
use Pusher\PusherException;

class NotificationAppointmentCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The systems will notify for staff about upcoming appointments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try {
            $count = $this->getNotification();
            $this->info($count . ' has been sent.');
        } catch (GuzzleException $e) {
            $this->info((string)$e->getMessage());
        } catch (ApiErrorException | PusherException $e) {
            $this->info((string)$e->getMessage());
        }
    }


    /**
     * @throws ApiErrorException
     * @throws GuzzleException
     * @throws PusherException
     */
    public function getNotification() {
        $timer = (int)Setting::getValueByKey(AppointmentSetting::TIMER) * 60;
        $users = User::all();
        $data  = [];
        foreach ($users as $user) {
            $notifications = $user->notifications;
            foreach ($notifications as $notification) {
                $appointment = $notification['data'];
                $time        = strtotime($appointment['time']);
                if ($appointment['status'] == Status::STATUS_PENDING) {
                    if ($time > time() && $time <= (time() + $timer)) {
                        $data[]                   = $appointment;
                        $appointment['status']    = Status::STATUS_ACTIVE;
                        $appointment['time_show'] = formatDate(time(), 'd-m-Y H:i:s');
                        $notification->update(['data' => $appointment]);
                    }
                }
            }
        }

        foreach ($data as $item) {
            Helper::dataPusher($item);
        }

        return count($data);
    }
}
