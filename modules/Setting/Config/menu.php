<?php
return [
    'name'       => trans('Settings'),
    'route'      => route('get.setting.list'),
    'sort'       => 99,
    'active'     => true,
    'icon'       => 'fas fa-cog',
    'middleware' => ['settings'],
    'group'      => [
        [
            'name'       => trans('File Manager'),
            'route'      => route('elfinder.index'),
            'sort'       => 1,
            'icon'       => 'fas fa-cog',
            'middleware' => ['setting-file-manager'],
        ]
    ]
];
