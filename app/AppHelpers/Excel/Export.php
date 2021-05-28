<?php

namespace App\AppHelpers\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Class Import
 * @package App\AppHelpers\Excel
 */
class Export implements FromCollection, WithHeadings{

    /**
     * @return Collection
     */
    public function collection(){
        return $this->collection;
    }

    /**
     * @param array $array
     * @return array
     */
    public function headings(): array{
        if(isset($this->headings)){
            return $this->headings;
        }
        return [];
    }
}
