<?php

namespace Modules\Setting\Model;

/**
 * Class MailConfig
 * @package Modules\Setting\Model
 */
class MailConfig extends Setting {
    const MAIL_DRIVER   = 'MAIL_DRIVER';
    const MAIL_HOST     = 'MAIL_HOST';
    const MAIL_PORT     = 'MAIL_PORT';
    const MAIL_USERNAME = 'MAIL_USERNAME';
    const MAIL_PASSWORD = 'MAIL_PASSWORD';
    const PROTOCOL      = 'MAIL_ENCRYPTION';
    const MAIL_ADDRESS  = 'MAIL_ADDRESS';
    const MAIL_NAME     = 'MAIL_NAME';

    const MAIL_CONFIG = [
        'MAIL_HOST',
        'MAIL_DRIVER',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_ENCRYPTION',
        'MAIL_ADDRESS',
        'MAIL_NAME'
    ];

    /**
     * @return array
     */
    public static function getMailConfig() {
        $mail_config = [];
        foreach(self::MAIL_CONFIG as $item) {
            $mail_config[$item] = self::getValueByKey($item);
        }

        return $mail_config;
    }
}
