<?php

namespace Modules\Setting\Model;

/**
 * Class MailConfig
 * @package Modules\Setting\Model
 */
class Website extends Setting{

    const LOGO     = 'LOGO';
    const BG_LOGIN = 'BG_LOGIN';

    const WEBSITE_CONFIG = [
        self::LOGO,
        self::BG_LOGIN
    ];

    /**
     * @return array
     */
    public static function getWebsiteConfig(){
        $setting = [];
        foreach(self::WEBSITE_CONFIG as $item){
            $mail_config[$item] = self::getValueByKey($item);
        }

        return $mail_config;
    }
}
