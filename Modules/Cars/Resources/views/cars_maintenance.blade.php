@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="carsMaintenance"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'carsMaintenances'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "carsMaintenance-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    image: function(row, column){
                            return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                            صورة المستند
                        </button>
                        <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">صورة المستند</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                 
                                    ${row.maintenances_image_url}
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;             
                    },
                    details: function(row, column){
                            return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                            تفاصيل الصيانات
                        </button>
                        <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">تفاصيل الصيانات</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                 
                                    ${row.details}
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;             
                    },
                    operations: function(row, column){
                        var operations = '';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="carsMaintenance-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف الصيانة"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });
            
            $('#datatable').on( "click",'button[data-action="carsMaintenance-create"]', function () { 
                $this = $(this);
                $('select[data-options_source="cars"]').change(function () { 
                $id = $(this).val();
              $.get($("meta[name='BASE_URL']").attr("content")  + '/cars/' + $id , function (response) {
                  $('input[data-operations-show-text="car.plate_number"]').val(response.plate_number);
                  $('input[data-operations-show-text="car.plate_number"]').attr('disabled','disabled')
              });

            });
            });
            $('#datatable').on( "click",'button[data-action="carsMaintenance-update"]', function () { 
                console.log($('input[data-operations-show-text="car.plate_number"]'));
                setInterval(() => {
                    $('input[data-operations-show-text="car.plate_number"]').attr('disabled','disabled');
                }, 1000);

            });
            $('#carsMaintenance').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'carsMaintenances'
                }
            });

            $('#carsMaintenance').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#carsMaintenance').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection