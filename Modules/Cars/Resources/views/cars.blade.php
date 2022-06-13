@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('modals')
    <div id="car"></div>
@endsection

@section('javascript')
    <script>
        let $row;
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'cars'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "car-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    operations: function(row, column){
                        $row = row;
                        var operations = '';
                        operations += '<button class="btn btn-falcon-default btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="car-show"><span class="fas fa-eye" data-fa-transform="shrink-3"></span></button>';
                        operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="car-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        return operations;
                    }
                }
            });
            setInterval(() => {
                $('button[data-action="car-update"]').click(function () { 
                $id = $(this).attr('data-id');
                
              $.get($("meta[name='BASE_URL']").attr("content")  + '/cars/' + $id , function (response) {
                  for (let index = 0; index < response.papers.length; index++) {
                      if(response.papers[index].type == "رخصة_سائق"){
                          console.log('1');
                        $('input[name="driver_license_stated_at"]').val(response.papers[index].stated_at)
                        $('input[name="driver_license_ended_at"]').val(response.papers[index].ended_at)
                      }else if(response.papers[index].type == "تأمين"){
                          console.log('2');
                        $('input[name="insurance_stated_at"]').val(response.papers[index].stated_at)
                        $('input[name="insurance_ended_at"]').val(response.papers[index].ended_at)
                      }else{
                        console.log('3');
                          $('input[name="driving_license_stated_at"]').val(response.papers[index].stated_at)
                        $('input[name="driving_license_ended_at"]').val(response.papers[index].ended_at)
                      }
                }
              })
                
             })
            }, 1000);
            $('#car').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'cars'
                }
            });

            $('#car').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#car').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
    </script>
@endsection