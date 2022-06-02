@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <!-- <div id="role"></div> -->
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'users/roles'
                },
                filters: {
                    enable: false
                },
                // datatable: {
                //     buttons: [
                //         {
                //             title: "جديد",
                //             data_action: "role-create",
                //             classes: {
                //                 button: "btn btn-falcon-default btn-sm",
                //                 icon: "fas fa-plus"
                //             }
                //         }
                //     ]
                // },
                formatters: {
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="role-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="role-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<a class="btn btn-falcon-default btn-sm mr-2" href="' + $("meta[name='BASE_URL']").attr("content") + '/users/permissions/manage?type=role&id=' + row.id + '" title="إدارة الصلاحيات"><span class="fas fa-fingerprint" data-fa-transform="shrink-3"></span></a>';
                        
                        return operations;
                    }
                }
            });

            // $('#role').briskForm({
            //     resource: {
            //         api: $("meta[name='BASE_URL']").attr("content"),
            //         entity: 'users/roles'
            //     }
            // });

            // $('#role').bind('briskForm.store.done', function(event, response){
            //     $("#datatable").briskDataTable('refresh');
            // });

            // $('#role').bind('briskForm.update.done', function(event, response){
            //     $("#datatable").briskDataTable('refresh');
            // });
        });
    </script>
@endsection