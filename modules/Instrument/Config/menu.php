<?php
return [
    'name'       => trans('Instrument'),
    'route'      => route('get.instrument.list'),
    'sort'       => 8,
    'active'     => TRUE,
    'id'         => 'instrument',
    'icon'       => 'fas fa-toolbox',
    'middleware' => ['instrument'],
    'group'      => []
];
