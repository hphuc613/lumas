<?php

use Illuminate\Support\Facades\App;
use Stichoza\GoogleTranslate\GoogleTranslate;

if(!function_exists('gg_trans')) {
    /**
     * @param $string
     * @return string|null
     * @throws ErrorException
     */
    function gg_trans($string): ?string {
        $target = (App::getLocale() === 'cn') ? 'zh-TW' : App::getLocale();
        if(!empty($target) && $target !== 'en') {
            $tr = new GoogleTranslate($target);
            return $tr->translate($string);
        }

        return $string;
    }
}
