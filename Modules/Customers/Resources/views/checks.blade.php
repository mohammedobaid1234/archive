@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="check"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'checks'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "check-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    customerProfile: function(row, column){
                        return '<a href="'+ $("meta[name='BASE_URL']").attr("content")+'/customers/'+ row.customer.id +'/profile" target="_blank">' + row.customer.full_name + '</a>';
                    },
                    additionalDetails : function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                            تفاصيل أخرى للشيك 
                        </button>
                        <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">تفاصيل أخرى للشيك</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.additional_details}
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    },
                    typeOfCheck: function (row, column) {
                        var value = '';

                        return value += "<label class='badge badge-primary ml-4'>" + row.type + "</label>";
                       

                    },
                    image: function(row, column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                                    صورة الشيك
                                </button>
                                <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">صورة الشيك</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" height="600px">
                                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.check_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
                                            </iframe>
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

                        operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="check-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="check-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });

            $('#check').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'checks'
                }
            });

            $('#check').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#check').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection