<?php
return [
    'name' => 'member',
    'display_name' => trans('Member'),
    'group' => [
        [
            'name'         => 'role-create',
            'display_name' => trans('Create new role'),
        ],
        [
            'name'         => 'role-update',
            'display_name' => trans('Update role'),
        ],
        [
            'name'         => 'role-delete',
            'display_name' => trans('Delete role'),
        ],
    ]
];
