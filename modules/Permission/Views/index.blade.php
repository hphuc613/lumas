@extends('Base::layouts.master')
@php
use Modules\Permission\Model\PermissionRole;

@endphp
@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans('Access Control') }}</a></li>
                </ol>
            </nav>
        </div>
        <!--Search box-->
        <div class="listing">
            <form action="" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="50px">#</th>
                                    <th>{{ trans('Name') }}</th>
                                    @foreach($roles as $role)
                                        <th>{{ trans($role->name) }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @php($key = 1)
                                @foreach($permissions as $permission)
                                    @if($permission->parent_id === 0)
                                        <tr>
                                            <td>{{ $key++ }}</td>
                                            <td><b>{{ trans($permission->display_name) }}</b></td>
                                            @foreach($roles as $role)
                                                <td>
                                                    <input type="checkbox" name='role_permission[{{$role->id}}][]' value="{{ $permission->id }}"
                                                           @if(\Modules\Permission\Model\PermissionRole::checkRolePermission($permission->id, $role->id)) checked @endif
                                                           @if(\Modules\Role\Model\Role::getAdminRole()->id === $role->id) disabled @endif
                                                           class="checkbox-style select-all select-all-with-other-child" id="role-{{ $permission->id }}-{{ $role->id }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                        @if(!empty($permission->child))
                                            @foreach($permission->child as $child)
                                                <tr>
                                                    <td>{{ $key++ }}</td>
                                                    <td><div class="ml-2">- {{ trans($child->display_name) }}</div></td>
                                                    @foreach($roles as $role)
                                                        <td>
                                                            <input type="checkbox" name='role_permission[{{$role->id}}][]' value="{{ $child->id }}"
                                                                   @if(\Modules\Permission\Model\PermissionRole::checkRolePermission($child->id, $role->id)) checked @endif
                                                                   @if(\Modules\Role\Model\Role::getAdminRole()->id === $role->id) disabled @endif
                                                                   class="checkbox-style checkbox-item role-{{ $permission->id }}-{{ $role->id }}" id="role-{{ $child->id }}-{{ $role->id }}">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="btn-group mt-3">
                            <button type="submit" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('form').submit(function (e) {
            var checkbox = $(this).find('input[type="checkbox"]');
            console.log(checkbox);
            $.each(checkbox, function (i, item) {
                $(item).removeAttr('disabled');
            });
        });
    </script>
@endpush
