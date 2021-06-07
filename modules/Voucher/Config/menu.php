<?php
return [
    'id'         => "voucher",
    'name'       => trans('Voucher'),
    'route'      => route('get.voucher.list'),
    'sort'       => 4,
    'active'     => true,
    'icon'       => 'fa fa-tag',
    'middleware' => ['voucher'],
    'group'      => []
];
