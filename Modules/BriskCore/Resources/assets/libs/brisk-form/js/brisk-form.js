(function ($) {
    $.fn.briskForm = function(argFunction) {
        var baseFunctions = {
            init: function(settings) {
                /**
                 * TODO: 
                 * - make common defaults and custom for each one
                 * - make sure from putting all available defualts here
                 * - check operations object defaults syntax
                 */
                var defaultInput = {
                    title: "",
                    name: "",
                    input: "input",
                    type: "text",
                    placeholder: "",
                    classes: [],
                    multiple: false,
                    required: false,
                    data: {
                        value: null
                    },
                    operations: {
                        show: {
                            text: "",
                            id: ""
                        },
                        update: {
                            active: true
                        }
                    },
                    maxlength: null,
                    date: false,
                    time: false,
                    month: false
                };

                var defaultSelect = {
                    title: "",
                    name: "",
                    input: "select",
                    placeholder: "",
                    classes: [],
                    multiple: false,
                    required: false,
                    data: {
                        options_source: "",
                        has_empty: false,
                        has_all: false,
                        has_none: false,
                        value: null,
                        autoload: true
                    },
                    operations: {
                        show: {
                            text: "",
                            id: ""
                        },
                        update: {
                            active: true
                        }
                    }
                };

                var defaultTextarea = {
                    title: "",
                    name: "",
                    placeholder: "",
                    classes: [],
                    required: false,
                    data: {
                        value: null
                    },
                    operations: {
                        show: {
                            text: "",
                            id: ""
                        },
                        update: {
                            active: true
                        }
                    },
                    rows: 3
                };

                var defaultSettings = {
                    resource: {
                        api: 'http://domain-name.com',
                        entity: 'resource',
                        create: 'create',
                        store: '',
                        update: ''
                    },
                    title: "إضافة بيانات جديدة",
                    title_show: "استعراض البيانات",
                    title_update: "تحديث البيانات",
                    store: {
                        url: function($this, url){
                            return url;
                        }
                    },
                    show: {
                        url: function($this, url){
                            return url;
                        },
                        done: function(response){}
                    },
                    update: {
                        url: function($this, url){
                            return url;
                        }
                    },
                    formatters: {
                        response: function(response) {
                            return response; 
                        }
                    }
                };

                var instanceSettings = $.extend(true, defaultSettings, settings);

                return this.each(function() {
                    var $this = $(this);
                    
                    $this.data('briskFormSettings', instanceSettings);
                    $this.data('briskFormDefaultInput', defaultInput);
                    $this.data('briskFormDefaultSelect', defaultSelect);
                    $this.data('briskFormDefaultTextarea', defaultTextarea);

                    internalFunctions.template.call(this);
                    internalFunctions.inputs.call(this);
                    internalFunctions.bindCreate.call(this);
                    internalFunctions.bindStore.call(this);
                    internalFunctions.bindShow.call(this);
                    internalFunctions.bindUpdate.call(this);
                });    
            }
        };

        var internalFunctions = {
            template: function(){
                var $this = $(this);
                var internalSettings = $this.data('briskFormSettings');

                $this.attr('class', 'modal fade fill-in');
                $this.attr('role', 'dialog');
                $this.attr('aria-hidden', 'true');

                var template_html = '';

                template_html += '    <div class="modal-dialog brisk-form" role="document">';
                template_html += '        <div class="modal-content">';
                template_html += '            <div class="modal-header py-2">';
                template_html += '                <h4 class="modal-title"></h4>';
                template_html += '                <button type="button" class="close" data-dismiss="modal">×</button>';
                template_html += '            </div>';
                template_html += '            <div class="modal-alerts"></div>';
                template_html += '            <form role="form" autocomplete="off">';
                template_html += '                <div class="modal-body"></div>';
                template_html += '                <div class="modal-footer sm-text-center">';
                template_html += '                    <button type="submit" class="btn btn-primary btn-block fs-15">حفظ البيانات</button>';
                template_html += '                    <button type="reset" class="d-none">Reset</button>';
                template_html += '                </div>';
                template_html += '            </form>';
                template_html += '        </div>';
                template_html += '    </div>';

                var $template_html = $('<div />', {html: template_html});

                $template_html.find('.modal-title').html(internalSettings.title);

                $this.html($template_html.html());
            },
            templateSetup: function(operation){
                var $this = $(this);
                        
                $this.find('form').trigger("reset");
                $this.find('select').val('').trigger('change');

                // try {
                //     $this.find('.select2').select2('data', null);
                // } catch (error) {
                    
                // }

                switch(operation){
                    case "create":
                        $this.attr('data-method', 'create');
                        $this.find('.modal-title').html("إضافة بيانات جديدة");
                        $this.find('input').prop('disabled', false);
                        $this.find('select').prop('disabled', false);
                        $this.find('textarea').prop('disabled', false);
                        $this.find('.select2').prop("disabled", false);
                        $this.find('.modal-footer').show();
                        $this.find('button:submit').attr('data-action', 'store');

                        $this.find('[data-operations-show-active="false"]').closest('div').removeClass("d-none");
                        $this.find('[data-operations-update-active="false"]').closest('div').removeClass("d-none");
                        break;
                    case "show":
                        $this.attr('data-method', 'show');
                        $this.find('.modal-title').html("استعراض البيانات");
                        $this.find('input').prop('disabled', true);
                        $this.find('select').prop('disabled', true);
                        $this.find('textarea').prop('disabled', true);
                        $this.find('.select2').prop("disabled", true);
                        $this.find('.modal-footer').hide();

                        $this.find('[data-operations-update-active="false"]').closest('div').removeClass("d-none");
                        $this.find('[data-operations-show-active="false"]').closest('div').addClass("d-none");
                        break;
                    case "update":
                        $this.attr('data-method', 'update');
                        $this.find('.modal-title').html("تحديث البيانات");
                        $this.find('input').prop('disabled', false);
                        $this.find('select').prop('disabled', false);
                        $this.find('textarea').prop('disabled', false);
                        $this.find('.select2').prop("disabled", false);
                        $this.find('.modal-footer').show();
                        $this.find('button:submit').attr('data-action', 'update');

                        $this.find('[data-operations-show-active="false"]').closest('div').removeClass("d-none");
                        $this.find('[data-operations-update-active="false"]').closest('div').addClass("d-none");
                        break;
                }

                $this.modal('show');
            },
            inputs: function() {
                var $this = $(this);
                var internalSettings = $this.data('briskFormSettings');

                var response = localStorage.getItem("BriskForm-[" + $("meta[name='BASE_URL']").attr("content") + "]-" + $this.attr('id'));

                if(response){
                    console.log("[briskForm][" + $this.attr('id') + "]: Loading from storage...");

                    response = JSON.parse(response);
                    
                    if(typeof internalSettings.formatters.response == 'function'){
                        response = internalSettings.formatters.response(response);
                    }

                    $.each(response.inputs, function(key, record) {
                        $this.find('.modal-body').append(internalFunctions.formatter.call($this[0], key, record));
                    });

                    if(internalSettings.title == ""){
                        if(response.title !== undefined){
                            internalSettings.title = response.title;
                        }
                    }

                    $this.find('.modal-title').html(internalSettings.title);

                    $this.find('[data-options_source]').each(function(){
                        if($(this).attr('data-autoload') == "false"){
                            return;
                        }
                        
                        try {
                            GLOBALS.lists[$(this).attr('data-options_source')](this);
                        } catch (error) {
                            console.error("[BriskForm]: lists[$(this).attr('data-options_source')](this): " + $(this).attr('data-options_source'));
                        }
                    });

                    $this.find('input[datepicker]').each(function(){
                        initDatetimepicker($(this));
                    });

                    $this.find('input[timepicker]').each(function(){
                        initTimepicker($(this));
                    });

                    setTimeout(() => {
    					$this.trigger('briskForm.build.done', response);
                    }, 400);
                }else{
                    console.log("[briskForm][" + $this.attr('id') + "]: Loading from server...");

                    $.ajax({
                        url: internalSettings.resource.api + '/' + internalSettings.resource.entity + '/' + internalSettings.resource.create,
                        type: "GET"
                    })
                    .done(function(response){
                        localStorage.setItem("BriskForm-[" + $("meta[name='BASE_URL']").attr("content") + "]-" + $this.attr('id'), JSON.stringify(response));

                        if(typeof internalSettings.formatters.response == 'function'){
                            response = internalSettings.formatters.response(response);
                        }
    
                        $.each(response.inputs, function(key, record) {
                            $this.find('.modal-body').append(internalFunctions.formatter.call($this[0], key, record));
                        });
    
                        if(internalSettings.title == ""){
                            if(response.title !== undefined){
                                internalSettings.title = response.title;
                            }
                        }
    
                        $this.find('.modal-title').html(internalSettings.title);
    
                        $this.find('.select2').each(function(){
                            if($(this).attr('data-autoload') == "false"){
                                return;
                            }
                            
                            try {
                                GLOBALS.lists[$(this).attr('data-options_source')](this);
                            } catch (error) {
                                console.error("[BriskForm]: lists[$(this).attr('data-options_source')](this): " + $(this).attr('data-options_source'));
                            }
                        });

                        $this.find('input[datepicker]').each(function(){
                            initDatetimepicker($(this));
                        });

                        $this.find('input[timepicker]').each(function(){
                            initTimepicker($(this));
                        });
    
                        $this.trigger('briskForm.build.done', response);
                    })
                    .fail(function() {
                    })
                    .always(function(){
                    });
                }
            }, 
            formatter: function(key, input) {
                var $this = $(this);
                var internalSettings = $(this).data('briskFormSettings');
                var briskFormDefaultInput = $(this).data('briskFormDefaultInput');
                var briskFormDefaultSelect = $(this).data('briskFormDefaultSelect');
                var briskFormDefaultTextarea = $(this).data('briskFormDefaultTextarea');
                var html = "";

                if(!$.isArray(input)){
                    var $input = "";
                    var template_html = '';

                    if(input.input == "input"){
                        input = $.extend({}, briskFormDefaultInput, input);

                        template_html += '<div class="row">';
                        template_html += '  <div class="col-12">';
                        template_html += '      <div class="form-group no-margin-hr">';
                        template_html += '          <label class="control-label" briskForm-input-name></label>';
                        template_html += '          <input type="" name="" placeholder="" class="form-control" autocomplete="off">';
                        template_html += '      </div>';
                        template_html += '  </div>';
                        template_html += '</div>';
    
                        $input = $('<div />', {html: template_html});
    
                        if(input.maxlength !== null){
                            $input.find('input').attr('maxlength', input.maxlength);
                        }
    
                        if(input.date){
                            $input.find('input').attr('datepicker', true);
                        }
    
                        if(input.time){
                            $input.find('input').attr('timepicker', true);
                        }
    
                        if(input.month){
                            $input.find('input').attr('monthpicker', true);
                        }

                        $input.find(input.input).attr('type', input.type);

                        if(input.type === "number"){
                            $input.find(input.input).attr('step', 'any');
                        }
                    }
                    if(input.input == "select"){
                        input = $.extend({}, briskFormDefaultSelect, input);

                        template_html += '<div class="row">';
                        template_html += '  <div class="col-12">';
                        template_html += '      <div class="form-group no-margin-hr">';
                        template_html += '          <label class="control-label" briskForm-input-name></label>';
                        template_html += '          <select type="" name="" placeholder="" class="form-control"></select>';
                        template_html += '      </div>';
                        template_html += '  </div>';
                        template_html += '</div>';
    
                        $input = $('<div />', {html: template_html});
                    }
                    if(input.input == "textarea"){
                        input = $.extend({}, briskFormDefaultTextarea, input);

                        template_html += '<div class="row">';
                        template_html += '  <div class="col-12">';
                        template_html += '      <div class="form-group no-margin-hr">';
                        template_html += '          <label class="control-label" briskForm-input-name></label>';
                        template_html += '          <textarea type="" name="" placeholder="" class="form-control" autocomplete="off"></textarea>';
                        template_html += '      </div>';
                        template_html += '  </div>';
                        template_html += '</div>';
    
                        $input = $('<div />', {html: template_html});
                        
                        $input.find(input.input).attr('rows', input.rows);
                    }

                    $input.find('[briskForm-input-name]').html(input.title + (input.required ? " (*)" : ""));
                    $input.find(input.input).attr('name', input.name);
                    $input.find(input.input).attr('placeholder', input.placeholder);

                    var classes = $input.find(input.input).attr('class');
                    $.each(input.classes, function(filter_key, filter_class){
                        classes += (classes == "" ? filter_class : " " + filter_class);
                    });
                    $input.find(input.input).attr('class', classes);

                    $.each(input.data, function(key, attribute){
                        $input.find(input.input).attr('data-' + key, attribute);
                    });

                    $.each(input.operations, function(key, procedure){
                        $.each(procedure, function(innerKey, value){
                            if($.trim(value) == ""){
                                return false;
                            }

                            $input.find(input.input).attr('data-operations-' + key + '-' + innerKey, value);
                        });
                    });
                        
                    if(Boolean(input.multiple)){
                        $input.find(input.input).attr('multiple', 'multiple');
                    }

                    if(input.required){
                        $input.find(input.input).attr('data-required', "true");
                    }

                    html += $input.html();
                }
                
                if($.isArray(input)){
                    var inputsArray = input;

                    var template_html = '';
        
                    template_html += '<div class="row">';

                    $(inputsArray).each(function(key, input){
                        var $input = $('<div />', {html: internalFunctions.formatter.call($this[0], key, input)}).find(' > .row');
                        var $column = $input.find('.col-12');
						$column.addClass("col-" + Math.round(12 / inputsArray.length));
                        $column.removeClass('col-12');
                        template_html += $input.html();
                    });

                    template_html += '</div>';

                    html += template_html;
                }

                return html;
            },
            show: function(id, update = false){
                var $this = $(this);
                var internalSettings = $this.data('briskFormSettings');
                var $submitButton = $this.find('button:submit');

                $submitButton.attr('data-original-html', $submitButton.html());
                $submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                $submitButton.prop('disabled', true);

                $.ajax({
                    url: internalSettings.show.url($this, internalSettings.resource.api + '/' + internalSettings.resource.entity + '/' + id),
                    dataType: "json",
                    type: 'GET',
                    cache: false,
                    success: function (response){
                        $this.find('[data-operations-show-text]').each(function(){
                            var show_text_target_column = $(this).attr('data-operations-show-text').split('.');
                            var show_text_value = response[show_text_target_column[0]];

                            if(show_text_target_column.length > 1){
                                $(show_text_target_column).each(function(key, record){
                                    if(key == 0 || show_text_value === undefined || show_text_value === null)
                                        return true;

                                    show_text_value = show_text_value[record]
                                })
                            }

                            if($(this).hasClass('select2')){
                                if($(this).attr('data-ajax') == "true" && !$(this).is('[multiple]') && $(this).attr('data-operations-show-id') !== undefined){ 
                                    var show_id_target_column = $(this).attr('data-operations-show-id').split('.');
                                    var show_id_value = response[show_id_target_column[0]];
                                    var $select = $(this);

                                    if(show_id_target_column.length > 1){
                                        $(show_id_target_column).each(function(key, record){
                                            if(key == 0 || show_id_value === undefined || show_id_value === null)
                                                return true;

                                            show_id_value = show_id_value[record]
                                        })
                                    }

                                    // $(this).select2('data', {id: show_id_value, text: show_text_value});

                                    $select.append($("<option selected='selected'></option>").val(show_id_value).text(show_text_value));
                                    $select.trigger('change');
                                }

                                if($(this).attr('data-ajax') == "true" && $(this).is('[multiple]')){
                                    var multiple_values = [];
                                    var show_id_target_column = $(this).attr('data-operations-show-id').split('.');
                                    var multiple_show_text_target_column = $(this).attr('data-operations-show-text').split('.');
                                    var multiple_show_text_value = response[multiple_show_text_target_column[0]];
                                    var $select = $(this);

                                    // console.log(multiple_show_text_value);
                                    // console.log(show_id_target_column[show_id_target_column.length - 1]);
                                    // console.log(show_id_target_column.length + " show_id_target_column.length");

                                    $(multiple_show_text_value).each(function(key, record){
                                        var valueIndex = show_id_target_column.length - 1;
                                        var textIndex = multiple_show_text_target_column.length - 1;

                                        if(show_id_target_column.length === 3){
                                            var upperLevelValueIndex = show_id_target_column.length - 2;
                                            var upperLevelTextIndex = multiple_show_text_target_column.length - 2;

                                            var value = record[show_id_target_column[upperLevelValueIndex]][show_id_target_column[valueIndex]];
                                            var text = record[multiple_show_text_target_column[upperLevelTextIndex]][multiple_show_text_target_column[textIndex]];
                                        }else{
                                            var value = record[show_id_target_column[valueIndex]];
                                            var text = record[multiple_show_text_target_column[textIndex]];
                                        }

                                        $select.append($("<option selected='selected'></option>").val(value).text(text));
                                    });

                                    $select.trigger('change');
                                }

                                if($(this).attr('data-ajax') !== "true" && !$(this).is('[multiple]')){
                                    // $(this).attr('data-value', show_text_value);
                                    // $(this).val(show_text_value).trigger('change');
                                    
                                    if($(this).attr('data-autoload') == "false"){
                                        $(this).append($("<option selected='selected'></option>").val(show_id_value).text(show_text_value));
                                        $(this).trigger('change');
                                    }else{
                                        $(this).val(show_text_value).trigger('change');
                                    }
                                }

                                /***
                                 * TODO: Make it able to access many level of childs
                                 */
                                if($(this).attr('data-ajax') !== "true" && $(this).is('[multiple]')){
                                    var multiple_values = [];
                                    var show_id_target_column = ($.trim($(this).attr('data-operations-show-id')) !== "" ? $(this).attr('data-operations-show-id').split('.') : null);
                                    var multiple_show_text_target_column = $(this).attr('data-operations-show-text').split('.');
                                    var multiple_show_text_value = response[multiple_show_text_target_column[0]];
                                    var $select = $(this);

                                    if(multiple_show_text_target_column.length == 3){
                                        multiple_show_text_value = multiple_show_text_value[multiple_show_text_target_column[1]];
                                    }

                                    $(multiple_show_text_value).each(function(key, record){
                                        if(show_id_target_column){
                                            var value = record[show_id_target_column[show_id_target_column.length - 1]];
                                            var text = record[multiple_show_text_target_column[multiple_show_text_target_column.length - 1]];

                                            if($select.find('option[value="' + value + '"]').length){
                                                $select.find('option[value="' + value + '"]').prop('selected', true);
                                            }else{
                                                $select.append($("<option selected='selected'></option>").val(value).text(text));
                                            }
                                        }else{
                                            multiple_values.push(record[multiple_show_text_target_column[1]]);
                                        }
                                    });

                                    if(show_id_target_column){
                                        $(this).attr('data-value', multiple_values);
                                    }else{
                                        $(this).val(multiple_values);
                                    }

                                    $select.trigger('change');
                                }

                                if(update){
                                    if($(this).is('[data-operations-update-active="false"]')){
                                        $(this).select2('enable', false);
                                    }
                                }
                            }else{
                                $(this).val(show_text_value);

                                if(update){
                                    if($(this).is('[data-operations-update-active="false"]')){
                                        $(this).prop('disabled', true);
                                    }
                                }
                            }
                        });

                        internalSettings.show.done(response);
                    },
                    error: function (response){
                        http.fail(response.responseJSON, true);
                    }
                })
                .always(function(){
                    $submitButton.html($submitButton.attr('data-original-html'));
                    $submitButton.prop('disabled', false);
                });
            },
            bindCreate: function(){
                var $this = $(this);

                $(document).on('click', '[data-action="' + $this.attr('id') + '-create"]', function(){
                    internalFunctions.templateSetup.call($this[0], "create");
                });
            },
            bindShow: function(){
                var $this = $(this);

                $(document).on('click', '[data-action="' + $this.attr('id') + '-show"]', function(){
                    var $button = $(this);

                    internalFunctions.templateSetup.call($this[0], "show");
                    internalFunctions.show.call($this[0], $button.attr('data-id'));
                });
            },
            bindUpdate: function(){
                var $this = $(this);

                $(document).on('click', '[data-action="' + $this.attr('id') + '-update"]', function(){
                    var $button = $(this);

                    internalFunctions.templateSetup.call($this[0], "update");
                    internalFunctions.show.call($this[0], $button.attr('data-id'), update = true);
                    $this.attr('data-id', $button.attr('data-id'));
                });
            },
            bindStore: function(){
                var $this = $(this);
                var internalSettings = $this.data('briskFormSettings');

                $this.submit(function(event){
                    event.preventDefault();

                    var $this = $(this);
                    var $form = $(this).find('form');

                    if(!internalFunctions.validation.call($this[0])){
                        return;
                    }

                    var posted_data = {};
                    var posted_data_serializeArray = $form.serializeArray();
                    var formData = new FormData();

                    $.each(posted_data_serializeArray, function(key, data){
                        if(posted_data[data.name] == undefined){
                            posted_data[data.name] = data.value;
                        }else{
                            posted_data[data.name] += ", " +  data.value;
                        }
                    });

                    $.each($.fn.briskForm.defaults.store.request.data, function(name, value) {
                        posted_data[name] = value;
                    });

                    if($this.find('button:submit').attr('data-action') == 'store'){
                        $this.trigger('briskForm.store.request', posted_data);
                    }

                    if($this.find('button:submit').attr('data-action') == 'update'){
                        $this.trigger('briskForm.update.request', posted_data);
                    }

                    $.each(posted_data, function (key, value){
                        if($.trim(value) !== ""){
                            formData.append(key, value);
                        }
                    });
                    
                    $form.find("input[type='file']").each(function (key, input){
                        $.each(input.files, function (key, file){
                            formData.append($(input).attr('name') + '[' + key + ']', file);
                        });
                    });

                    var URL = {};

                    if($this.find('button:submit').attr('data-action') == 'store'){
                        URL.href = internalSettings.store.url($this, internalSettings.resource.api + '/' + internalSettings.resource.entity + (internalSettings.resource.store == "" ? "" : '/' + internalSettings.resource.store));
                        $this.trigger('briskForm.store.url', URL);
                    }
                    
                    if($this.find('button:submit').attr('data-action') == 'update'){
                        URL.href = internalSettings.update.url($this, internalSettings.resource.api + '/' + internalSettings.resource.entity + '/' + $this.attr('data-id') + (internalSettings.resource.update == "" ? "" : '/' + internalSettings.resource.update));
                        $this.trigger('briskForm.update.url', URL);
                        formData.append('_method', 'PUT');
                    }
                    
                    var storeBefore = {
                        validate: true
                    };
                    
                    $this.trigger('briskForm.store.before', storeBefore);
                    
                    if(!storeBefore.validate){
                        return;
                    }

                    // notifications.loading.show();

                    var $submitButton = $this.find('button:submit');

                    $submitButton.attr('data-original-html', $submitButton.html());
                    $submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                    $submitButton.prop('disabled', true);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: URL.href,
                        // url: $this.trigger('briskForm-store-url', instanceSettings.resource.api + '/' + instanceSettings.resource.entity + (instanceSettings.resource.store == "" ? "" : '/' + instanceSettings.resource.store)),
                        data: formData,
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (response){
                            $form.trigger("reset");
                            $form.find('select').val('').trigger('change');
                            try {
                                $this.find('.select2').select2('data', null);
                            } catch (error) {
                                
                            }
                            $this.modal('hide');

                            http.success(response);
                            
                            if($this.find('button:submit').attr('data-action') == 'store'){
                                $this.trigger('briskForm.store.done', response);
                            }
                            if($this.find('button:submit').attr('data-action') == 'update'){
                                $this.trigger('briskForm.update.done', response);
                            }
                        },
                        error: function (response){
                            http.fail(response.responseJSON, true);
                        }
                    })
                    .always(function(){
                        $submitButton.html($submitButton.attr('data-original-html'));
                        $submitButton.prop('disabled', false);
                    });
                });
            },
            validation: function(){
                var $this = $(this);
                $this.find('.modal-alerts').html('');

                $this.find('[name]').each(function(key, input){
                    var label = $(this).closest('div').find('label').html();
                    var message = '';
    
                    if(!$(this).closest('.form-group').hasClass("d-none") && $(this).is('[data-required="true"]') && !$.trim($(this).val()).length){
                        message = "يرجى التأكد من ادخال البيانات";
                        $this.find('.modal-alerts').append('<div class="alert alert-danger" style="margin-bottom: 0; padding: 5px 15px; border-radius: 0;"><strong>' + label + '</strong> ' + message + '.</div>');
                    }
                });

                return ($.trim($this.find('.modal-alerts').html()) == "" ? true : false);
            }
        };

        // Functions dynamic calling methodology
        if (baseFunctions[argFunction]) {
            return baseFunctions[argFunction].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof argFunction === 'object' || !method) {
            return baseFunctions.init.apply(this, arguments);
        } else {
            $.error("Function " + method + " doesn’t exist!");
        }
    };

    $.fn.briskForm.defaults = {
        store: {
            request: {
                data: {
                    _token: $("meta[name='csrf-token']").attr("content")
                }
            }
        }
    }
}(jQuery));