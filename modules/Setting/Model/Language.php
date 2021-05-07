<?php

namespace Modules\Setting\Model;

class Language extends Setting
{
    const LANGUAGE = 'LANGUAGE';

    public function getLanguage(){
        $language = $this->getValueByKey(self::LANGUAGE);
    }
}
