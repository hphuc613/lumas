<?php

namespace App\AppHelpers\Excel;

use Maatwebsite\Excel\Concerns\ToModel;

/**
 * Class Import
 * @package App\AppHelpers\Excel
 */
class Import implements ToModel{

    /**
     * @param array $row
     * @return array
     */
    public function model(array $row){
        return $row;
    }
}
