@extends('layouts.app')

@section('css')
@endsection

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="employee"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'employees'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "employee-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    roles: function (row, column) {
                        var value = '';

                        $(row.user.roles).each(function(key, role){
                         return   value += "<label class='badge badge-primary ml-2'>" + role.label + "</label>";
                        });

                        return (value == '' ? "-" : value);
                    },
                    operations: function(row, column){
                        var operations = '';

                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="employee-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="reset-password" title="إعادة ضبط كلمة المرور"><span class="fas fa-key" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف الموظف"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';

                        return operations;
                    }
                }
            });
            $("#datatable").on('click', '[data-action="reset-password"]', function(){
                var $this = $(this);

                swal({
                    title: "هل أنت متأكد من أنك تريد إعادة ضبط كلمة مرور حساب الموظف؟",
                    text: "سيتم إعادة ضبط كلمة مرور بوابة الموظف وارسالها في رسالة نصية على الرقم الشخصي.",
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

                    $.post($("meta[name='BASE_URL']").attr("content") + '/employees/' + $this.attr('data-id') + '/reset-password', {
                        _token: $("meta[name='csrf-token']").attr("content")
                    },
                    function(response){
                        http.success({ 'message': response.message });
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
            
            $('#employee').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'employees'
                }
            });

            $('#employee').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#employee').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection
