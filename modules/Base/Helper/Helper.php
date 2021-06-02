<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Stichoza\GoogleTranslate\GoogleTranslate;

if(!function_exists('gg_trans')){
    /**
     * @param $string
     * @return string|null
     * @throws ErrorException
     */
    function gg_trans($string, $locale = null): ?string{
        if(!empty($locale)){
            $tr = new GoogleTranslate($locale);
            return $tr->translate($string);
        }
        $target = (App::getLocale() === 'cn') ? 'zh-TW' : App::getLocale();
        if(!empty($target) && $target !== 'en'){
            $tr = new GoogleTranslate($target);
            return $tr->translate($string);
        }

        return $string;
    }
}
if(!function_exists('formatDate')){
    /**
     * @param $timestamp
     * @param null $format
     * @return string|null
     */
    function formatDate($timestamp, $format = null): ?string{
        if(!empty($format)){
            return Carbon::createFromTimestamp($timestamp)->format($format);
        }
        return Carbon::createFromTimestamp($timestamp)->format("d-m-Y");
    }
}

if(!function_exists('generateQRCode')){
    /**
     * @param $data
     * @param null $format
     * @return string|null
     */
    function generateQRCode($data, $format = 'svg'): ?string{
        return QrCode::format($format)->generate($data);
    }
}

if(!function_exists('calculateTimeNotification')){
    /**
     * @param $data
     * @param null $format
     * @return string|null
     */
    function calculateTimeNotification($data): ?string{
        $time_ago = time() - strtotime($data);

        $day    = (int)formatDate($time_ago, 'd');
        $hour   = (int)formatDate($time_ago, 'H');
        $minute = (int)formatDate($time_ago, 'i');

        if($day == 1){
            if($hour < 24){
                if($hour < 2){
                    if($minute < 60){
                        $time = ($minute <= 1) ? 'a minute' : $minute . ' minutes';
                    }else{
                        $time = $hour . ' hour';
                    }
                }else{
                    $time = $hour . ' hours';
                }
            }
        }else{
            $time = ($day == 2) ? $day - 1 . ' day' : $day - 1 . ' days';
        }

        return $time;
    }
}

if(!function_exists('summaryListing')){
    /**
     * @param $data
     * @return string|null
     */
    function summaryListing($data): ?string{
        $html = '';
        $html .= '<span class="listing-information">';
        $html .= trans('Showing');
        $html .= '<b> ';
        $html .= (count($data) > 0) ? ($data->currentpage() - 1) * $data->perpage() + 1 : 0;
        $html .= '</b> ';
        $html .= trans(' to ');
        $html .= '<b> ';
        $html .= ($data->currentpage() - 1) * $data->perpage() + $data->count();
        $html .= ' </b>';
        $html .= trans(' of ');
        $html .= '<b>' . $data->total() . '</b> ' . trans('entries') . '</span>';

        return $html;
    }
}
