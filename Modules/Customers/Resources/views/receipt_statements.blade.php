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
    <div id="receipt_statement"></div>
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
                    entity: 'receipt_statements'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "receipt_statement-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    opposite: function(row, column){
                 return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#receipt_statements-opposite-${row.id}">
                            المقابل
                        </button>
                        <div class="modal fade" id="receipt_statements-opposite-${row.id}" tabindex="-1" role="dialog" aria-labelledby="receipt_statements-opposite-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">المقابل</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.opposite}
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    },
                    other_terms:function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#receipt_statements-other-terms${row.id}">
                           شروط متغيرة
                        </button> 
                        <div class="modal fade" id="receipt_statements-other-terms${row.id}" tabindex="-1" role="dialog" aria-labelledby="receipt_statements-other-terms${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">شروط متغيرة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.other_terms}
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    }, 
                    image : function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#image-of-receipt_statement-${row.id}">
                            صورة إيصال القبض 
                        </button>
                        <div class="modal fade" id="image-of-receipt_statement-${row.id}" tabindex="-1" role="dialog" aria-labelledby="image-of-receipt_statement-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle"> صورة إيصال القبض </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    <iframe src="https://docs.google.com/viewerng/viewer?url=${row.receipt_statement_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
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
                        operations += '<a target="_blank" href="/customers/' + row.customer.id + '/profile" class="btn btn-falcon-info btn-sm mr-2" type="button" data-id="' + row.id + '"><span class="fas fa-user" data-fa-transform="shrink-3"></span></a>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="receipt_statement-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف العقد"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                       
                        return operations;
                    }
                }
            });
           

            $('#receipt_statement').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'receipt_statements'
                }
            });

            $('#receipt_statement').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#receipt_statement').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#datatable').addClass('table-bordered');
        });
    </script>

@endsection
