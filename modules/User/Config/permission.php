<?php
return [
    'name'         => 'users',
    'display_name' => trans('Users'),
    'group'        => [
        [
            'name'         => 'user-create',
            'display_name' => trans('Create user'),
        ],
        [
            'name'         => 'user-update',
            'display_name' => trans('Update User'),
        ],
        [
            'name'         => 'user-delete',
            'display_name' => trans('Delete User'),
        ],
        [
            'name'         => 'update-user-role',
            'display_name' => trans('Update User Role'),
        ],
    ]
];
