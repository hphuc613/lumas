<?php
return [
    'id'         => "voucher",
    'name'       => trans('Voucher'),
    'route'      => route('get.voucher.list'),
    'sort'       => 1,
    'active'     => false,
    'icon'       => 'fa fa-tag',
    'middleware' => ['voucher'],
    'group'      => []
];
