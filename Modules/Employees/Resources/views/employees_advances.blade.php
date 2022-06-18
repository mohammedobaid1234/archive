@extends('layouts.app')

@section('css')
@endsection

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="EmployeesAdvance"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'EmployeesAdvances'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "EmployeesAdvance-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    operations: function(row, column){
                        var operations = '';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="EmployeesAdvance-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف الفاتورة"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });
            
            $('#EmployeesAdvance').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'EmployeesAdvances'
                }
            });

            $('#EmployeesAdvance').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#EmployeesAdvance').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection
