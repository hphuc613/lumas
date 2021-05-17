<?php
return [
    [
        'name'         => 'service-type',
        'display_name' => trans('Service Type'),
        'group'        => [
            [
                'name'         => 'service-type-create',
                'display_name' => trans('Create service type'),
            ],
            [
                'name'         => 'service-type-update',
                'display_name' => trans('Update service type'),
            ],
            [
                'name'         => 'service-type-delete',
                'display_name' => trans('Delete service type'),
            ],
        ]
    ],
    [
        'name'         => 'service',
        'display_name' => trans('Service'),
        'group'        => [
            [
                'name'         => 'service-create',
                'display_name' => trans('Create service'),
            ],
            [
                'name'         => 'service-update',
                'display_name' => trans('Update service'),
            ],
            [
                'name'         => 'service-delete',
                'display_name' => trans('Delete service'),
            ],
        ]
    ]
];
