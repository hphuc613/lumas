<?php
return [
    [
        'name'         => 'member',
        'display_name' => trans('Client'),
        'group'        => [
            [
                'name'         => 'member-update',
                'display_name' => trans('Update Client'),
            ],
            [
                'name'         => 'member-delete',
                'display_name' => trans('Delete Client'),
            ],
            [
                'name'         => 'member-add-service',
                'display_name' => trans('Add Product For Member'),
            ],
        ]
    ],
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
    ]
];
