<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Setting\Model\MailConfig;

class MailConfigServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        if(Schema::hasTable('settings')) {
            $settings = DB::table('settings')->get();

            $mail = [];
            foreach($settings as $item) {
                $mail[$item->key] = $item->value;
            }

            if($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => $mail[MailConfig::MAIL_DRIVER] ?? env('MAIL_MAILER'),
                    'host'       => $mail[MailConfig::MAIL_HOST] ?? env('MAIL_HOST'),
                    'port'       => $mail[MailConfig::MAIL_PORT] ?? env('MAIL_HOST'),
                    'from'       => [
                        'address' => $mail[MailConfig::MAIL_ADDRESS] ?? env('MAIL_FROM_ADDRESS'),
                        'name'    => $mail[MailConfig::MAIL_NAME] ?? env('MAIL_FROM_NAME'),
                    ],
                    'encryption' => $mail[MailConfig::PROTOCOL] ?? env('MAIL_ENCRYPTION'),
                    'username'   => $mail[MailConfig::MAIL_USERNAME] ?? env('MAIL_USERNAME'),
                    'password'   => $mail[MailConfig::MAIL_PASSWORD] ?? env('MAIL_PASSWORD'),
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}
