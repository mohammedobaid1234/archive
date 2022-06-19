@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="otherPaper"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'otherPapers'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "otherPaper-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    notes : function(row,column){
                        return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                            تفاصيل إخرى   
                        </button>
                        <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">تفاصيل إخرى  </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" height="600px">
                                    ${row.note}
                                 </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                                </div>
                            </div>
                        </div>`;
                    },
                
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
                                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.other_paper_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
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

                        operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="otherPaper-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="otherPaper-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });

            $('#otherPaper').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'otherPapers'
                }
            });

            $('#otherPaper').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#otherPaper').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection