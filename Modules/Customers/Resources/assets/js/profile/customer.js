var customer_id = $('#customer-main-tabs').attr('data-customer-id');

$(function() {
    GLOBALS.lists.provinces($('[data-options_source="provinces"]'));

    $('#customer-main-tabs > .nav-bar [data-tab-hash]').click(function () {
        location.hash = $(this).attr('data-tab-hash');

        $('#customer-main-tabs [data-tab]').removeClass('active');
        $('#customer-main-tabs [data-tab="' + location.hash + '"]').addClass('active');
    });

    if (location.hash.length > 0) {
        $('#customer-main-tabs [data-tab-hash="' + location.hash.substr(1, location.hash.length) + '"]').trigger('click');
    }

    $("#customer-settings").on('click', '[data-action="loginAs"]', function(){
        window.open($("meta[name='BASE_URL']").attr("content") + "/loginAsCustomer/" + $(this).attr('data-id'), '_blank');
    });

    $("#customer-settings").on('click', '[data-action="customer-update"]', function(){
        
        var province_id = Number($('#customer-data-modal').attr('data-province-id'));
        var province_name = $('#customer-data-modal').attr('data-province-name');

        if(province_id){
            if($('#customer-data-modal [name="province_id"]').find('option[value="' + province_id + '"]').length){
                $('#customer-data-modal [name="province_id"]').find('option[value="' + province_id + '"]').prop('selected', true).trigger('change');
            }else{
                $('#customer-data-modal [name="province_id"]').append($("<option selected='selected'></option>").val(province_id).text(province_name));
            }
        }

        $('#customer-data-modal').modal('show');
    });

    $("#customer-data-modal").on('submit', function(event){
        event.preventDefault();

        var $this = $(this);
        var buttonText = $this.find('button:submit').text();

        $this.find("button:submit").attr('disabled', true);
        $this.find("button:submit").html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        var posted_data = {
            _method: "PUT",
            _token: $("meta[name='csrf-token']").attr("content"),
            mobile_no: $this.find("input[name='mobile_no']").val(),
            province_id: $this.find("select[name='province_id']").val(),
            address: $this.find("input[name='address']").val(),
            type: $this.find("select[name='type']").val(),
            company_name: $this.find("input[name='company_name']").val(),
        }

        $.post($("meta[name='BASE_URL']").attr("content") + "/customers/" + customer_id, posted_data,
        function (response, status) {
            window.location.reload();
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.find("button:submit").attr('disabled', false);
            $this.find("button:submit").html(buttonText);
        });
    });

    $("#customer-settings").on('click', '[data-action="status"]', function(){
        var $this = $(this);

        var title = "";

        if($this.attr('data-type') == "activate"){
            title = "هل أنت متأكد من أنك تريد تفعيل حساب (" +  $.trim($this.attr('data-full-name')) + ")؟";
            text = "فور إعادة تفعيل الحساب يتم تفعيل إمكانية الدخول إلى الموقع الإلكتروني.";
        }

        if($this.attr('data-type') == "deactivate"){
            title = "هل أنت متأكد من أنك تريد تجميد حساب (" +  $.trim($this.attr('data-full-name')) + ")؟";
            text = "فور تجميد الحساب يتم تعطيل إمكانية الدخول إلى الموقع الإلكتروني فقط.";
        }

        swal({
            title: title,
            text: text,
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

            $.post($("meta[name='BASE_URL']").attr("content") + '/customers/' + $this.attr('data-id') + '/status', {
                _token: $("meta[name='csrf-token']").attr("content"),
                type: $this.attr('data-type')
            },
            function(response){
                http.success({ 'message': response.message });
                window.location.reload();
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

    $("#customer-settings").on('click', '[data-action="reset-password"]', function(){
        var $this = $(this);

        swal({
            title: "هل أنت متأكد من أنك تريد إعادة ضبط كلمة مرور حساب (" +  $.trim($this.attr('data-full-name')) + ")؟",
            text: "سيتم إعادة ضبط كلمة مرور الحساب وارسالها في رسالة نصية على الرقم الشخصي.",
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

            $.post($("meta[name='BASE_URL']").attr("content") + '/customers/' + $this.attr('data-id') + '/reset-password', {
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

    $("#customer-settings").on('click', '[data-action="send-sms"]', function(){
        var $this = $(this);

        swal({
            title: "هل تريد إرسال رسالة للعميل " + $this.attr('data-full-name') + " ؟",
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

            $.post($("meta[name='BASE_URL']").attr("content") + '/customers/' + $this.attr('data-id') + '/send-sms', {
                _token: $("meta[name='csrf-token']").attr("content"),
                content: $.trim($('.swal-modal textarea').val()),
            },
            function(response){
                http.success(response);
            })
            .fail(function(response){
                http.fail(response.responseJSON, true);
            })
            .always(function(){
                $this.html(oldText);
                $this.prop('disabled', false);
            });
        });

        $('.swal-modal .swal-title').after('<div class="px-2"><textarea type="text" class="form-control" placeholder="نص الرسالة..."></textarea></div>');
    });

    // ------------------- products -----------------

    $("#datatable-products").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/products'
        },
        formatters: {
            operations: function(row, column){
                var operations = '';

                if(!row.approved_by){
                    operations += '<button class="btn btn-falcon-success btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="approve" title="قبول المنتج"><span class="fas fa-check" data-fa-transform="shrink-3"></span></button>';
                }

                operations += '<a href="/products/' + row.id + '/edit" class="btn btn-falcon-primary btn-sm mr-2" type="button" data-id="' + row.id + '"><span class="fas fa-edit" data-fa-transform="shrink-3"></span></a>';

                if(row.deactivated_by){
                    operations += '<button class="btn btn-falcon-success btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="status" data-type="activate" title="تفعيل المنتج"><span class="fas fa-lock-open" data-fa-transform="shrink-3"></span></button>';
                }else{
                    operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="status" data-type="deactivate" title="تجميد المنتج"><span class="fas fa-lock" data-fa-transform="shrink-3"></span></button>';
                }

                operations += '<button class="btn btn-falcon-danger btn-sm mr-2" type="button" data-id="' + row.id + '" data-action="remove" title="حذف المنتج"><span class="fas fa-trash" data-fa-transform="shrink-3"></span></button>';

                return operations;
            }
        }
    });

    $("#datatable-products").on('click', '[data-action="status"]', function(){
        var $this = $(this);

        var title = "";

        if($this.attr('data-type') == "activate"){
            title = "هل أنت متأكد من أنك تريد تفعيل المنتج؟";
            text = "فور إعادة تفعيل المنتج يتم ظهور المنتج في الموقع.";
        }

        if($this.attr('data-type') == "deactivate"){
            title = "هل أنت متأكد من أنك تريد تجميد المنتج؟";
            text = "فور تجميد المنتج يتم اخفاء المنتج من الموقع.";
        }

        swal({
            title: title,
            text: text,
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

            $.post($("meta[name='BASE_URL']").attr("content") + '/products/' +$this.attr('data-id') + '/status', {
                _token: $("meta[name='csrf-token']").attr("content"),
                type: $this.attr('data-type')
            },
            function(response){
                http.success({ 'message': response.message });
                $("#datatable-products").briskDataTable('refresh');
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

    $("#datatable-products").on('click', '[data-action="remove"]', function(){
        var $this = $(this);

        var title = "هل أنت متأكد من أنك تريد حذف المنتج؟";
        var text = "فور حذف المنتج يتم اخفاء المنتج من الموقع ولا يمكن لصاحب المنتج إعادت.";

        swal({
            title: title,
            text: text,
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

            $.ajax({
                url: $("meta[name='BASE_URL']").attr("content") + '/products/' + $this.attr('data-id'),
                method: 'DELETE',
                dataType: 'json',
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-Token': $("meta[name='csrf-token']").attr("content") ,
                    "Accept": "application/json"
                },
                error: function (response) {
                    http.fail(response.responseJSON, true);
                    $this.html(oldText);
                    $this.prop('disabled', false);
                },
            })
            .done(function(response){
                http.success({ 'message': response.message });
                $("#datatable-products").briskDataTable('refresh');
                $this.html(oldText);
                $this.prop('disabled', false);
            });
        });
    });

    $("#datatable-products").on('click', '[data-action="approve"]', function(){
        var $this = $(this);

        title = "هل أنت متأكد من أنك تريد قبول المنتج؟";
        text = "فور قبول المنتج يتم ظهور المنتج في الموقع.";

        swal({
            title: title,
            text: text,
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

            $.post($("meta[name='BASE_URL']").attr("content") + '/products/' +$this.attr('data-id') + '/approve', {
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            function(response){
                http.success({ 'message': response.message });
                $("#datatable-products").briskDataTable('refresh');
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

    // ------------------- notifications -----------------

    $("#datatable-notifications").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/notifications'
        },
        formatters: {
            content: function(row, column){
                if(row.type_notifty.id == 1 || row.type_notifty.id == 4){
                    return '<span style="font-weight:700">' + row.data.product_name +': ' +'</span>' + row.data.content;
                }else{
                    return row.data.content;
                }
            },
            url: function(row, column){
                return '<a target="_blank" href="' + "http://qasetli.local" + row.data.url + '">' + "http://qasetli.local" + row.data.url + '</a>';
            },
        }
    });
    // ------------------- sms -----------------

    $("#datatable-smses").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/smses'
        },
    });
    // ------------------- products-views -----------------

    $("#datatable-products-views").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/products-views'
        },
    });

    // ------------------- wishlists -----------------

    $("#datatable-wishlists").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/wishlists'
        },
    });

    // ------------------- carts -----------------

    $("#datatable-carts").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/carts'
        },
    });

    $('#datatable-carts').bind("briskDataTable.get.done", function(){
        var customer_total_number_products = 0;
        var customer_total_price_after_discount = 0;

        $("#datatable-carts tr").each(function(){
            var $this = $(this);

            if($this.closest('tr').find('[data-column-title="quantity"]').html() !== undefined){
                customer_total_number_products += Number($this.closest('tr').find('[data-column-title="quantity"]').html());
            }

            if($this.closest('tr').find('[data-column-title="total_price_after_discount"]').html() !== undefined){
                customer_total_price_after_discount += Number($this.closest('tr').find('[data-column-title="total_price_after_discount"]').html());
            }
        });

        $('#customer_total_number_products').html(customer_total_number_products);
        $('#customer_total_price_after_discount').html(customer_total_price_after_discount);
    });
});
