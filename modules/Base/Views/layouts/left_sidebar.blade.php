<?php

use App\AppHelpers\Helper;

$menu = Helper::config_menu_merge();
?>
<div class="left-sidebar">
    <div class="menu-sidebar">
        <ul class="list-unstyled">
        @if(!empty($menu))
            @foreach($menu as $item)
                @can($item['middleware'])
                    @if($item['active'])
                        @if(!empty($item['group']))
                            <!--Has child-->
                                <li class="li-has-child">
                                    <a class="menu-link has-child" data-toggle="collapse"
                                       href="#{{ $item['id'] }}-child"
                                       aria-expanded="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="menu-parent">
                                                <i class="{{ !empty($item['icon']) ? $item['icon'] : null }}"></i>
                                                <span
                                                    class="title-link-has-child">{{ !empty($item['name']) ? trans($item['name']) : 'N/A' }}</span>
                                            </div>
                                            <span class="title-link-has-child"><i class="fas fa-angle-down"></i></span>
                                        </div>
                                    </a>
                                    <ul class="collapse list-unstyled ml-3 child" id="{{ $item['id'] }}-child">
                                        @foreach($item['group'] as $child)
                                            @can($child['middleware'])
                                            <li>
                                                <a href="{{ $child['route'] }}" id="{{ $child['id'] }}"
                                                   class="menu-link-child">
                                                    <span>{{ trans($child['name']) }}</span>
                                                </a>
                                            </li>
                                            @endcan
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ !empty($item['route']) ? $item['route'] : '#' }}" id="{{ $item['id'] }}"
                                       class="menu-link">
                                        <i class="{{ !empty($item['icon']) ? $item['icon'] : null }}"></i>
                                        <span
                                            class="title-link">{{ !empty($item['name']) ? trans($item['name']) : 'N/A' }}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endcan
                @endforeach
            @endif
            @env('local')
                <li>
                    <a href="https://fontawesome.com/icons?d=gallery" target="_blank">
                        <i class="fas fa-flag"></i>
                        <span class="title-link">Fontawesome</span>
                    </a>
                </li>
                <li>
                    <a href="https://getbootstrap.com/docs/4.0/getting-started/introduction/" target="_blank">
                        <i class="fab fa-bootstrap"></i>
                        <span class="title-link">Bootstrap4</span>
                    </a>
                </li>
            @endenv
        </ul>
    </div>
</div>
