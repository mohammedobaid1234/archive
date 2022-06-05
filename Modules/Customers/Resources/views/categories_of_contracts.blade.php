@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="categories_of_contract"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'categories_of_contracts'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "categories_of_contract-create",
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

                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="categories_of_contract-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف العميل"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });

            $('#categories_of_contract').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'categories_of_contracts'
                }
            });

            $('#categories_of_contract').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#categories_of_contract').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection