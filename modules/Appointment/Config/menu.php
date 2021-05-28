<?php
return [
    'id'         => 'appointment',
    'name'       => trans('Appointment'),
    'route'      => route('get.appointment.list'),
    'sort'       => 6,
    'active'     => true,
    'icon'       => 'fas fa-calendar-check',
    'middleware' => ['appointment'],
    'group'      => []
];
