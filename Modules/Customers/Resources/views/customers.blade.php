@extends('layouts.app')

@section('css')
    <style>
        .no-character{
            font-size: 15px;
        }
    </style>
@endsection

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="customer"></div>
@endsection

@section('javascript')
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/html2canvas.min.js') }}"></script>
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/canvg/3.13.0/canvg.js') }}"></script>
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/qrcode.min.js') }}"></script>
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/jsbarcode/3.11.5/JsBarcode.all.min.js') }}"></script>

    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'customers'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "customer-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    customer: function(row, column){
                        return '<a href="'+ $("meta[name='BASE_URL']").attr("content")+'/customers/'+ row.id +'/profile" target="_blank">' + row.full_name + '</a>';
                    },
                    operations: function(row, column){
                        var operations = '';
                        operations += '<a target="_blank" href="/customers/' + row.id + '/profile" class="btn btn-falcon-info btn-sm mr-2" type="button" data-id="' + row.id + '"><span class="fas fa-user" data-fa-transform="shrink-3"></span></a>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="customer-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });


            $('#customer').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'customers'
                }
            });

            $('#customer').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#customer').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection
