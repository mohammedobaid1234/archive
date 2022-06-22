@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="electricitie"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'electricities'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "electricitie-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    value: function (row,column) { 
                        return row.current_reading - row.previous_reading;
                    },
                    price:function (row,column) { 
                        if(row.type == 'خط24'){
                            return (row.current_reading - row.previous_reading);
                        }else if(row.type == 'خط8'){
                            return (row.current_reading - row.previous_reading) * 0.6;
                        }
                    },
                    operations: function(row, column){
                        var operations = '';

                        operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="electricitie-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="electricitie-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });
            
            $('#electricitie').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'electricities'
                }
            });

            $('#electricitie').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#electricitie').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
            
            setTimeout(() => {
                $('button[data-action="electricitie-create"]').click(function () { 
                    setTimeout(()=>$('input[data-operations-show-text="previous_reading"]').attr('disabled', 'disabled'),500);
                    $('select[data-options_source="electronic_type"]').on('change', function () { 
                        $val = $(this).val();
                        if($val != null){
                            $.get($("meta[name='BASE_URL']").attr("content")  + '/electricities/latest/' + $val , function (response) {
                            $('input[data-operations-show-text="previous_reading"]').val(response.current_reading);
                            });
                        }
                    });
             })
            }, 1000);
        });
    </script>
@endsection