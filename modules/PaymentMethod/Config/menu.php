<?php
return [
    'name'       => trans('Payment Method'),
    'route'      => route('get.payment_method.list'),
    'sort'       => 8,
    'active'     => TRUE,
    'id'         => 'payment-method',
    'icon'       => 'far fa-credit-card',
    'middleware' => [],
    'group'      => []
];
