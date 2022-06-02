var briskCoreDataLastUpdate = localStorage.getItem("briskCoreDataLastUpdate");
var briskCoreDataLastUpdateDate = (new Date()).toDateString();

console.log('[BriskCore][Data][LastUpdateDate]: ' + briskCoreDataLastUpdateDate);
if(!briskCoreDataLastUpdate || briskCoreDataLastUpdate !== briskCoreDataLastUpdateDate){
    briskCoreClearLocalStorage();
    localStorage.setItem("briskCoreDataLastUpdate", briskCoreDataLastUpdateDate);
}

function briskCoreClearLocalStorage(){
    const localStorageItems = { ...localStorage };
    console.log(localStorageItems);
    Object.entries(localStorageItems).forEach(function(key, value){
        var prefixes = [
            "BriskDataTable-[" + $("meta[name='BASE_URL']").attr("content") + "]-",
            "BriskForm-[" + $("meta[name='BASE_URL']").attr("content") + "]-",
            "BriskSelectOption-[" + $("meta[name='BASE_URL']").attr("content") + "]-"
        ];

        prefixes.forEach(function(prefix, index){
            if(prefixes.includes(key[0].substr(0, prefix.length))){
                localStorage.removeItem(key[0]);
            }
        });
    });
}

$(document).on('click', '[data-action="hard-reload"]', function(){
    briskCoreClearLocalStorage();
    location.reload(true);
});

$(document).on('click', '[data-action="reload"]', function(){
    location.reload();
});

$(document).on('click', '[data-action="logout"]', function(){
    $.post($("meta[name='BASE_URL']").attr("content") + "/logout", {
        _token: $("meta[name='csrf-token']").attr("content")
    })
    .always(function() {
        location.reload();
    });
});

function initDatetimepicker($element = null){
    if(!$element){
        $element = $('.datetimepicker');
    }

    $element.length && $element.each(function (index, value) {
        var $this = $(value);
        var options = $.extend({
            locale: "ar",
            dateFormat: 'Y-m-d',
            disableMobile: true,
            allowInput: true,
        }, $this.data('options'));
        $this.flatpickr(options);
    });
}

function initTimepicker($element = null){
    if(!$element){
        $element = $('.timepicker');
    }

    $element.length && $element.each(function (index, value) {
        var $this = $(value);
        var options = $.extend({
            locale: "ar",
            dateFormat: 'H:i',
            enableTime: true,
            noCalendar: true,
            disableMobile: true,
            allowInput: true,
        }, $this.data('options'));
        $this.flatpickr(options);
    });
}

flatpickr.l10ns.default.firstDayOfWeek = 6;
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$('[data-action="navbar-filter"]').on('keyup', function(event){
    if (event.which === 27) {
        $(this).val("");
    }

    var value = $(this).val().toLowerCase();
    value = value.replace('أ', 'ا');
    value = value.replace('إ', 'ا');
    value = value.replace('ة', 'ه');
    value = value.replace('ي', 'ى');

    $(".navbar-vertical-content li").filter(function() {
        var text = $(this).text();
        text = text.replace('أ', 'ا');
        text = text.replace('إ', 'ا');
        text = text.replace('ة', 'ه');
        text = text.replace('ي', 'ى');

        $(this).toggle(text.toLowerCase().indexOf(value) > -1)
    });

    if($.trim($(this).val()) == ""){
        $('.navbar-vertical-content').find('.navbar-vertical-divider').show();
    }else{
        $('.navbar-vertical-content').find('.navbar-vertical-divider:not(:last)').hide();
    }
});

$(function(){
    $('#change-password').briskForm({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'employees/change-password'
        },
    });

    $('form').bind('reset', function(){ //to clear select2 properly
        $(this).find('select').val('').trigger('change');
    });
});

$(function(){
    $('#change-name').briskForm({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'employees/change-name'
        },
    });
});

$(function(){
    $('#change-image').briskForm({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'employees/change-image'
        },
    });
});

function getURLParameter(parameter){
    let url = new URL(window.location.href);
    let searchParams = new URLSearchParams(url.search);
    return searchParams.get(parameter);
}

$(function(){
    $('#iframe-print').on('load', function(){
        var response = $(this).contents().find('body pre').html();

        if(response !== undefined){
            response = JSON.parse(response);
            http.fail(response, true);
        }
    });
});

$(function(){
    $('#person').on('briskForm.build.done', function(response){
        $('#person').find('.modal-dialog').addClass('modal-lg');
    });
});

$(function(){
    $(document).on('click', '[data-action="change-number-of-seconds"]', function(){
        $('#changeNumberOfSecondsModal').modal('show');
    });

    $('#changeNumberOfSecondsModal').on('click', '[data-action="save-change"]', function(event){
        event.preventDefault();

        var $this = $(this);
        var buttonText = $this.text();

        $this.attr('disabled', true);
        $this.html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        $.post($("meta[name='BASE_URL']").attr("content") + "/employees/change-number-of-seconds", {
            _token: $("meta[name='csrf-token']").attr("content"),
            number_of_seconds: $.trim($('#changeNumberOfSecondsModal').find("input[name='number_of_seconds']").val()),
        },
        function (response, status) {
            $('#changeNumberOfSecondsModal').modal('hide');
            http.success({ 'message': response.message });
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.attr('disabled', false);
            $this.html(buttonText);
        });
    });

    $(document).on('click', '[data-action="change-image"]', function(){
        $('#changeImageModal').modal('show');
    });

    $('#changeImageModal').on('click', '[data-action="save-change"]', function(event){
        event.preventDefault();

        var $this = $(this);
        var buttonText = $this.text();

        $this.attr('disabled', true);
        $this.html('<span class="fas fa-spinner" data-fa-transform="shrink-3"></span>');

        $.post($("meta[name='BASE_URL']").attr("content") + "/employees/change-image", {
            _token: $("meta[name='csrf-token']").attr("content"),
            file: $.trim($('#changeImageModal').find("input[name='image']").val()),
        },
        function (response, status) {
            $('#changeImageModal').modal('hide');
            http.success({ 'message': response.message });
        })
        .fail(function (response) {
            http.fail(response.responseJSON, true);
        })
        .always(function () {
            $this.attr('disabled', false);
            $this.html(buttonText);
        });
    });
});
