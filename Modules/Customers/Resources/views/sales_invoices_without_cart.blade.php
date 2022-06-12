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
    <div id="sales_invoices_without_cart"></div>
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
                    entity: 'sales_invoices_without_carts'
                },
                formatters: {
                    customerProfile: function(row, column){
                        return '<a href="'+ $("meta[name='BASE_URL']").attr("content")+'/customers/'+ row.customer.id +'/profile" target="_blank">' + row.Ccustomer.full_name + '</a>';
                    },
                    sales_invoice: function(row, column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                                    صورة الفاتورة
                                </button>
                                <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">صورة الفاتورة</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" height="600px">
                                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.sale_invoice_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
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
                    operations: function(row, column){
                        var operations = '';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف العميل"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                        
                        return operations;
                    }
                }
            });
            
            });
    </script>
@endsection
