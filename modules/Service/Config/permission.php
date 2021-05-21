<?php
return [
    [
        'name'         => 'service-type',
        'display_name' => trans('Service Type'),
        'group'        => [
            [
                'name'         => 'service-type-create',
                'display_name' => trans('Create Service Type'),
            ],
            [
                'name'         => 'service-type-update',
                'display_name' => trans('Update Service Type'),
            ],
            [
                'name'         => 'service-type-delete',
                'display_name' => trans('Delete Service Type'),
            ],
        ]
    ],
    [
        'name'         => 'service',
        'display_name' => trans('Service'),
        'group'        => [
            [
                'name'         => 'service-create',
                'display_name' => trans('Create Service'),
            ],
            [
                'name'         => 'service-update',
                'display_name' => trans('Update Service'),
            ],
            [
                'name'         => 'service-delete',
                'display_name' => trans('Delete Service'),
            ],
        ]
    ]
];
