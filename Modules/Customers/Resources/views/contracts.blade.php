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
    <div id="contract"></div>
@endsection

@section('javascript')
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/canvg/3.13.0/canvg.js') }}"></script>
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/qrcode.min.js') }}"></script>
    <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/jsbarcode/3.11.5/JsBarcode.all.min.js') }}"></script>

    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'contracts'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "contract-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    contract_image: function(row, column){
                 return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                            صورة العقد
                        </button>
                        <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">صورة العقد</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    <iframe src="https://docs.google.com/viewerng/viewer?url=${row.contract_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
                                    </iframe>
                                     <iframe src="https://docs.google.com/viewerng/viewer?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true" frameborder="0"  style="height:500px;"  width="100%">
                                     </iframe>
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    },
                    contentForProduct:function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                            تفاصيل إضافية للمولد
                        </button> 
                        <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle"> تفاصيل إضافية للمولد</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.product_for_user.other_details}
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    }, 
                    contentForContract : function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                            تفاصيل أخرى للعقد 
                        </button>
                        <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">تفاصيل أخرى للعقد</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.note.content}
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
                        operations += '<a target="_blank" href="/customers/' + row.customer.id + '/profile" class="btn btn-falcon-info btn-sm mr-2" type="button" data-id="' + row.id + '"><span class="fas fa-user" data-fa-transform="shrink-3"></span></a>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="contract-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف العقد"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                       
                        return operations;
                    }
                }
            });
           

            $('#contract').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'contracts'
                }
            });

            $('#contract').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#contract').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#datatable').addClass('table-bordered');
        });
    </script>

@endsection
