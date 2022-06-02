@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('/public/themes/Falcon/v2.8.0/libs/jstree/3.3.11/themes/default/style.min.css') }}" />
@endsection

@section('content')
    <div class="row no-gutters bg-100 rounded-soft px-card py-2 mt-2 mb-3">
        <div class="col d-flex align-items-center">
            <h5 class="mb-0">سجل الصلاحيات</h5>
        </div>
    </div>
    <!-- <div class="row mb-2">
        <div class="col-md-8 col-xs-12 text-right">
            <h2 class="m-b-25">سجل الصلاحيات</h2>
        </div>

        <div class="col-md-4 col-xs-12">
            @if(\Auth::user()->can('users_module_permissions_management_store'))
            <button class="btn btn-primary float-left" data-action="permission-create">صلاحية جديدة</button>
            @endif
            @if(\Auth::user()->can('users_module_permissions_management_group_store'))
            <button class="btn btn-primary float-left ml-2" data-action="permissionGroup-create">مجموعة صلاحيات جديدة</button>
            @endif
        </div>
    </div> -->

    <div class="row no-gutters" id="permission-groups" data-permission-type="{{ $permissions['type'] }}" data-permission-id="{{ $permissions['id'] }}"></div>
    <!-- <div class="row no-gutters bg-100 rounded-soft px-card py-2 mt-2 mb-3" id="permission-groups"></div> -->

    {{--
    <div class="row">
        @foreach($permissions['groups'] as $key => $group)
        <div class="col-md-4 col-xs-12 mb-4">
            <div class="card card-default">
                <div class="card-heading">
                    {{ $group->name_ar }}
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($group->permissions as $permission)
                            @if(\Auth::user()->can($permission->name))
                                <div class="col-12" permission>
                                    <label class="float-right" data-en="{{ $permission->name }}" permission-name>
                                        {{ $permission->name_ar }}
                                        <br/>
                                        <code style="font-size: 70%;">({{ $permission->name }})</code>
                                    </label>

                                    @if(isset($permissions['type']) && isset($permissions['id']))
                                    <div class="float-left">
                                        <input type="checkbox" class="js-switch" data-init-plugin="switchery" data-size="small" data-color="primary"
                                        @if($permissions['type'] == 'user')
                                            @if(\Modules\Users\Entities\User::find($permissions['id'])->can($permission->name))
                                                checked="checked"
                                            @endif
                                        @endif
                                        @if($permissions['type'] == 'role')
                                            @if(\Spatie\Permission\Models\Role::find($permissions['id'])->hasPermissionTo($permission->name))
                                                checked="checked"
                                            @endif
                                        @endif
                                        />
                                    </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row no-gutters bg-100 rounded-soft px-card py-2 mt-2 mb-3">
        <div class="col d-flex align-items-center">
            <h5 class="mb-0">صلاحيات أخرى</h5>
        </div>
    </div>

    <div class="row text-right mb-4" other-permissions>
        <!-- <div class="col-lg-12">
            <h2 class="m-b-25">صلاحيات أخرى</h2>
        </div> -->
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        @foreach($permissions['notGrouped'] as $permission)
                            @if(\Auth::user()->can($permission->name))
                                <div class="col-lg-4" permission>
                                    <label class="float-right" data-en="{{ $permission->name }}" permission-name>{{ $permission->name_ar }}</label>

                                    @if(isset($permissions['type']) && isset($permissions['id']))
                                    <div class="float-left">
                                        <input type="checkbox" class="js-switch" data-init-plugin="switchery" data-size="small" data-color="primary"
                                        @if($permissions['type'] == 'user')
                                            @if(\Modules\Users\Entities\User::find($permissions['id'])->can($permission->name))
                                                checked="checked"
                                            @endif
                                        @endif
                                        @if($permissions['type'] == 'role')
                                            @if(\Spatie\Permission\Models\Role::find($permissions['id'])->hasPermissionTo($permission->name))
                                                checked="checked"
                                            @endif
                                        @endif
                                        />
                                    </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}
@endsection

@section('javascript')
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/jstree/3.3.11/jstree.min.js') }}"></script>

    <script>
        $(function(){
            $.jstree.defaults.core.themes.icons = "";

            $.get($("meta[name='BASE_URL']").attr("content") + '/users/permissions?type={{ $permissions["type"] }}&id={{ $permissions["id"] }}', function(response){
                var permission_groups = '';

                $(response.all_permissions).each(function(){
                    permission_groups += '<div class="col-md-4 mb-3 pl-0 pr-3">';
                    permission_groups += '      <div class="bg-100 rounded-soft overflow-auto" permission-group>';
                    permission_groups += '          <ul>';
                    permission_groups += groupNode(this);
                    permission_groups += '          </ul>';
                    permission_groups += '      </div>';
                    permission_groups += '</div>';
                });

                $('#permission-groups').html(permission_groups);

                $('[permission-group]').jstree({
                    "core" : {
                        "themes": {
                            "variant" : "large"
                        }
                    },
                    "checkbox": {
                        "keep_selected_style" : false
                    },
                    "plugins": ($.trim($('#permission-groups').attr('data-permission-type')) !== "" && $.trim($('#permission-groups').attr('data-permission-id')) !== "" ? ["wholerow", "checkbox"] : [])
                });

                $('[permission-group]').jstree('open_all');

                $(response.target_permissions).each(function(){
                    $('#permission-groups [data-name="' + this.name + '"]').find('.jstree-icon.jstree-checkbox').trigger('click', {loading: true});
                });
            });

            function groupNode(group){
                var node = '';

                node += '<li data-jstree=\'{"checkbox_disabled":true}\'>' + group.name_ar;
                // node += '<li data-name="' + this.name_en + '">' + group.name_ar;

                if(group.permissions && group.permissions.length){
                    node += '<ul>';

                    $(group.permissions).each(function(){
                        node += '<li data-name="' + this.name + '">' + this.label + '</li>';
                    });

                    node += '</ul>';
                }

                if(group.all_children_groups.length){
                    node += '<ul>';

                    $(group.all_children_groups).each(function(){
                        node += groupNode(this);
                    });

                    node += '</ul>';
                }

                node += '</li>';

                return node;
            }

            // $('#permission-groups').on('click', '[data-jstree=\'{"checkbox_disabled":true}\']', function(event){
            //     event.preventDefault();
            //     console.log("hi");
            // });

            $('#permission-groups').on('click', '[data-name]', function(event, value = {loading: false}){
            // $('#permission-groups').on('click', '[data-name] .jstree-icon.jstree-checkbox', function(event, value = {loading: false}){
                if(value.loading){
                    return;
                }

                if($.trim($('#permission-groups').attr('data-permission-type')) == "" || $.trim($('#permission-groups').attr('data-permission-id')) == ""){
                    return;
                }

                var $this = $(this);

                // var permissions = [];

                // /*
                // permissions.push($this.closest('[data-name]'));

                // $this.closest('[data-name]').find('[data-name]').each(function(){
                //     permissions.push($(this).closest('[data-name]'));
                // });
                // */

                // permissions.push($this.attr('data-name'));

                // $this.find('[data-name]').each(function(){
                //     permissions.push($(this).attr('data-name'));
                // });

                // console.log(permissions);

                http.loading();

                $.post($("meta[name='BASE_URL']").attr("content") + "/users/permissions/" + $('#permission-groups').attr('data-permission-id'), {
                    _method: "PUT",
                    _token: $("meta[name='csrf-token']").attr("content"),
                    name: $this.closest('[data-name]').attr('data-name'),
                    type: $('#permission-groups').attr('data-permission-type'),
                    value: $this.closest('li').attr('aria-selected')
                },
                function(response, status){
                    http.success({ 'message': response.message });
                })
                .fail(function(response) {
                    http.fail(response.responseJSON);
                });
            });
        });
    </script>
@endsection
