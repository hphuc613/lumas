<?php
return [
    'id'         => "voucher",
    'name'       => trans('Voucher'),
    'route'      => route('get.service_voucher.list'),
    'sort'       => 4,
    'active'     => false,
    'icon'       => 'fa fa-tag',
    'middleware' => ['voucher'],
    'group'      => []
];
