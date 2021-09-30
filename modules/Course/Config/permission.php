<?php
return [];
return [
    [
        'name'         => 'course-category',
        'display_name' => trans('Course Category'),
        'group'        => [
            [
                'name'         => 'course-category-create',
                'display_name' => trans('Create Course Category'),
            ],
            [
                'name'         => 'course-category-update',
                'display_name' => trans('Update Course Category'),
            ],
            [
                'name'         => 'course-category-delete',
                'display_name' => trans('Delete Course Category'),
            ],
        ]
    ],
    [
        'name'         => 'course',
        'display_name' => trans('Course'),
        'group'        => [
            [
                'name'         => 'course-create',
                'display_name' => trans('Create Course'),
            ],
            [
                'name'         => 'course-update',
                'display_name' => trans('Update Course'),
            ],
            [
                'name'         => 'course-delete',
                'display_name' => trans('Delete Course'),
            ],
        ]
    ]
];
