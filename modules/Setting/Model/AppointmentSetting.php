<?php

namespace Modules\Setting\Model;

/**
 * Class MailConfig
 * @package Modules\Setting\Model
 */
class AppointmentSetting extends Setting
{

    const TIMER = 'TIMER';

    const APPOINTMENT_CONFIG = [self::TIMER,];

    /**
     * @return array
     */
    public static function getAppointmentConfig()
    {
        $data = [];
        foreach (self::APPOINTMENT_CONFIG as $item) {
            $data[$item] = self::getValueByKey($item);
        }

        return $data;
    }
}
