@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="customerPaymentsDate"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'customerPaymentsDates'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "customerPaymentsDate-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    states: function (row, column) {
                      return  "<label class='badge badge-primary ml-2'>" + row.state + "</label>";
                    },
                    operations: function(row, column){
                        var operations = '';

                        // operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="customerPaymentsDate-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="change-state"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                         
                        return operations;
                    }
                }
            });
            $("#datatable").on('click', '[data-action="change-state"]', function(){
                var $this = $(this);

                swal({
                    title: "هل أنت متأكد من تغير حالة العملية؟",
                    text: "في حال كانت الحالة تم السداد ستتحول إلى لم يتم السداد والعكس.",
                    icon: "warning",
                    dangerMode: true,
                    buttons: ["إلغاء العملية", "تنفيذ الإجراء"]
                })
                .then((process) => {
                    if(!process){
                        return;
                    }

                    var oldText = $this.html();
                    $this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                    $this.prop('disabled', true);

                    http.loading();

                    $.post($("meta[name='BASE_URL']").attr("content") + '/customerPaymentsDates/' + $this.attr('data-id') + '/stateChange', {
                        _token: $("meta[name='csrf-token']").attr("content")
                    },
                    function(response){
                        http.success({ 'message': response.message });
                        $("#datatable").briskDataTable('refresh');
                    })
                    .fail(function(response){
                        http.fail(response.responseJSON, true);
                    })
                    .always(function(){
                        $this.html(oldText);
                        $this.prop('disabled', false);
                    });
                });
            });
            $('#customerPaymentsDate').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'customerPaymentsDates'
                }
            });

            $('#customerPaymentsDate').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#customerPaymentsDate').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection