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
     * @param $string
     * @return string|null
     * @throws ErrorException
     */
    function formatDate($data, $format = null): ?string{
        if(!empty($format)){
            return Carbon::parse($data)->format($format);
        }
        return Carbon::parse($data)->format("d-m-Y");
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
