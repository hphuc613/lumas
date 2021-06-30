<?php
return [
    'id'         => 'setting',
    'name'       => trans('Settings'),
    'sort'       => 12,
    'active'     => true,
    'icon'       => 'fas fa-cog',
    'middleware' => [],
    'group'      => [
        [
            'id'         => 'setting',
            'name'       => trans('Settings'),
            'route'      => route('get.setting.list'),
            'middleware' => ['setting-basic'],
        ],
        [
            'id'         => 'file-manager',
            'name'       => trans('File Manager'),
            'route'      => route('elfinder.index'),
            'middleware' => ['setting-file-manager'],
        ]
    ]
];
