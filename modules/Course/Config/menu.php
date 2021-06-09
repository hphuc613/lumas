<?php
return [
    'id'         => 'course',
    'name'       => trans('Course Management'),
    'route'      => route('get.course.list'),
    'sort'       => 4,
    'active'     => true,
    'icon'       => 'fas fa-book',
    'middleware' => ['course'],
    'group'      => [
        [
            'id'         => 'course-category',
            'name'       => trans('Course Category'),
            'route'      => route('get.course_category.list'),
            'middleware' => ['course-category'],
        ],
        [
            'id'         => 'course',
            'name'       => trans('Course'),
            'route'      => route('get.course.list'),
            'middleware' => ['course'],
        ],
        [
            'id'         => 'course-voucher',
            'name'       => trans('Course Voucher'),
            'route'      => route('get.course_voucher.list'),
            'middleware' => 'course-voucher'
        ]
    ]
];
