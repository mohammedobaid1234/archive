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
                    entity: 'users/sessions'
                },
                formatters: {
                    operations: function (row, column) {
                        return '<button class="btn btn-falcon-danger btn-sm" data-id="' + row.user.id + '" data-action="destroy" title="تسجيل خروج إجباري"><i class="fa fa-times"></i></button>';
                    }
                }
            });
        });
    </script>
@endsection