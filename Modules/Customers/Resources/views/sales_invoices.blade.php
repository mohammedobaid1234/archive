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
    <div id="sales_invoice"></div>
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
                    entity: 'sales_invoices'
                },
                datatable: {
                    buttons: [
                        {
                            title: "جديد",
                            data_action: "sales_invoice-create",
                            classes: {
                                button: "btn btn-falcon-default btn-sm",
                                icon: "fas fa-plus"
                            }
                        }
                    ]
                },
                formatters: {
                    sales_invoice: function(row, column){
                        return '<a href="'+ $("meta[name='BASE_URL']").attr("content")+'/sales_invoices/'+ row.id +'/profile" target="_blank">' + row.full_name + '</a>';
                    },
                    customer: function(row, column){
                        return '<a href="'+ $("meta[name='BASE_URL']").attr("content")+'/customers/'+ row.sale_invoice.customer.id +'/profile" target="_blank">' + row.sale_invoice.customer.full_name + '</a>';
                    },
                    operations: function(row, column){
                        var operations = '';
                        // operations += '<a target="_blank" href="/sales_invoices/' + row.id + '/profile" class="btn btn-falcon-info btn-sm mr-2" type="button" data-id="' + row.id + '"><span class="fas fa-user" data-fa-transform="shrink-3"></span></a>';
                        // operations += '<button class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="sales_invoice-update"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></button>';
                        // operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="destroy" title="حذف العميل"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';
                        
                        return operations;
                    }
                }
            });
            
            $('button[data-action="sales_invoice-create"]').click(function () {
                
                $row =$('input[data-operations-show-text="price_of_unit"]').parent().closest('.col-4');
                $('input[data-operations-show-text="price_of_unit"]').attr('data-action', 'tabPress');
                $('input[data-operations-show-text="price_of_unit"]').parent().closest('.col-4').addClass('price');
                $('select[data-options_source="products"]').change(function () { 
                    $this = $(this);
                    $value = $(this).val();
                    if($value != null){
                        $.get($("meta[name='BASE_URL']").attr("content") + '/products/' + $value, function(response){
                            $parent = $this.closest(".row").children(".price").children('div').children('input').val(response.price);
                        })
                    }
               
                });
                $(".form-group").on('click', 'input[name="price_of_unit[]"]', function(e) {
                });
                $(".form-group").on('keydown', 'input[data-action="tabPress"]', function(e) { 
                        var keyCode = e.keyCode || e.which; 
                        if (keyCode == 9 || keyCode == 13 || keyCode == 40) { 
                            e.preventDefault();
                            appends();
                            $(this).attr('data-action', '');
                        }
                });
                        
                

             });
             
            $('#sales_invoice').briskForm({
                resource: {
                    api: $("meta[name='BASE_URL']").attr("content"),
                    entity: 'sales_invoices'
                }
            });

            $('#sales_invoice').bind('briskForm.store.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });

            $('#sales_invoice').bind('briskForm.update.done', function(event, response){
                $("#datatable").briskDataTable('refresh');
            });
        });
        //appends row
        function appends(){
            $('.modal-body').last().append(`
            <div class="row">
            <div class="col-4"> 
                <div class="form-group no-margin-hr">
                    <select class="js-example-basic-single" data-options_source="products" name="product_id[]" "data-action="select-product">
                    </select>
                </div>
            </div> 
            <div class="col-4">
                <div class="form-group no-margin-hr">
                    <input type="text" name="quantity[]" placeholder="" class="form-control child" autocomplete="off" data-operations-show-text="quantity">  
                </div>
            </div>
            <div class="col-4 price">
                <div class="form-group no-margin-hr"> 
                    <label class="control-label" briskform-input-name=""></label>
                    <input type="text" name="price_of_unit[]" placeholder="" class="form-control child" autocomplete="off" data-operations-show-text="price_of_unit"> 
                </div>
            </div>
            </div>
            `);
            $(".form-group").on('keydown', 'input[data-action="tabPress"]', function(e) { 
            var keyCode = e.keyCode || e.which; 
                var keyCode = e.keyCode || e.which; 
                if (keyCode == 9 || keyCode == 13 || keyCode == 40) { 
                    e.preventDefault();
                    appends();
                    $(this).attr('data-action', '');
                }
            });
            $('input[data-operations-show-text="price_of_unit"]').attr('data-action', 'tabPress');

            $('.js-example-basic-single').select2();
            $.get($("meta[name='BASE_URL']").attr("content") + '/products', function(response){
                $('select[data-options_source="products"]').last().append(`<option value="> </option>`)
                for (let index = 0; index < response.data.length; index++) {
                    $('select[data-options_source="products"]').last().append(`<option value="${response.data[index].id}">${response.data[index].name}</option>`)
                }
            })
            $('select[data-options_source="products"]').change(function () { 

            $this = $(this);
            $value = $(this).val();
            if($value != null){
                $.get($("meta[name='BASE_URL']").attr("content") + '/products/' + $value, function(response){
                    $parent = $this.closest(".row").children(".price").children('div').children('input').val(response.price);
                })
            }
        
            });
        } 
    </script>
@endsection
