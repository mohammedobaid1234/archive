@extends('layouts.app')

@section('content')
    <div id="datatable"></div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#datatable").briskDataTable({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'users'
                },
                formatters: {
                    roles: function (row, column) {
                        var value = '';

                        $(row.roles).each(function(key, role){
                            value += "<label class='badge badge-primary ml-2'>" + role.label + "</label>";
                        });

                        return value;
                    }
                }
            });
        });
    </script>
@endsection