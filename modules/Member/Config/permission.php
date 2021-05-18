<?php
return [
    [
        'name'         => 'member-type',
        'display_name' => trans('Client Type'),
        'group'        => [
            [
                'name'         => 'member-type-create',
                'display_name' => trans('Create Client Type'),
            ],
            [
                'name'         => 'member-type-update',
                'display_name' => trans('Update Client Type'),
            ],
            [
                'name'         => 'member-type-delete',
                'display_name' => trans('Delete Client Type'),
            ],
        ]
    ],
    [
        'name'         => 'member',
        'display_name' => trans('Clients'),
        'group'        => [
            [
                'name'         => 'member-update',
                'display_name' => trans('Update Client'),
            ],
            [
                'name'         => 'member-delete',
                'display_name' => trans('Delete Client'),
            ],
        ]
    ]
];
