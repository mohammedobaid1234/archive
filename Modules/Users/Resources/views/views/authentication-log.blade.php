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
                    entity: 'users/authentication-log'
                }
            });
        });
    </script>
@endsection