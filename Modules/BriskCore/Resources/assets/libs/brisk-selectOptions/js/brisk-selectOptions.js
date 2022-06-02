(function ($) {
    $.fn.briskSelectOptions = function(argFunction) {
        var baseFunctions = {
            init: function(settings) {
                var defaultSettings = {
                    resource: 'http://domain-name.com/entity/list',
                    formatters: {
                        response: function(response) {
                            return response; 
                        }, 
                        option: {
                            value: "id",
                            title: "name"
                        }
                        //Or:
                        // option: function(key, record) {
                        //     return "<option value=\"" + record.id + "\">" + record.name + "</option>"; 
                        // }
                    },
                    options: null,
                    ajax: false,
                    additional_params: {}
                };

                var instanceSettings = $.extend(true, defaultSettings, settings);

                return this.each(function() {
                    var $this = $(this);
                    
                    $this.data('briskSelectOptionsSettings', instanceSettings);

                    // if($this.attr('data-autoload') !== "false"){
                    //     if($this.is('[data-options_source="active"]')){
                    //         console.log('reached');
                    //         console.log($.trim($this.html()));
                    //     }
                    // }

                    // if($this.is('select') && $.trim($this.html()) !== ""){
                    //     return;
                    // }

                    internalFunctions.get.call(this);
                });    
            },
            refresh: function() {
                return this.each(function() {
                    internalFunctions.get.call(this);
                });
            }
        };

        var internalFunctions = {
            get : function() {
                var $this = $(this);
                
                var internalSettings = $this.data('briskSelectOptionsSettings');
                    
                // if($this.attr('data-has_empty') === 'true'){
                //     $this.append("<option></option>");
                // }

                // if($this.attr('data-has_all') === 'true'){
                //     $this.append("<option value='all'>كافة الخيارات</option>");
                // }

                // if($this.attr('data-has_none') === 'true'){
                //     $this.append("<option value='none'>لا يوجد</option>");
                // }

                if(internalSettings.ajax){
                    $this.attr('data-ajax', "true");
                }

                if($this.is('[readonly]')){
                    $this.select2({disabled: 'readonly'});
                }

                if($this.attr('data-ajax') == "true"){
                    $this.select2({
                        dropdownParent: ($this.is('[data-dropdown-parent]') ? $this.attr('data-dropdown-parent') : ''),
                        placeholder: ($this.is('[data-placeholder]') && $.trim($this.attr('data-placeholder')) !== "" ? $this.attr('data-placeholder') : ""),
                        allowClear: true,
                        multiple: $this.is('[multiple]'),
                        ajax: {
                            url: internalSettings.resource,
                            dataType: 'json',
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    where_like_column: $.trim($this.attr('data-where_like_column')),
                                    page: params.page || 1
                                }

                                $.each(internalSettings.additional_params, function(key, value){
                                    query[key] = value;
                                });

                                return query;
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;
                
                                return {
                                    results: $.map(data.data, function (item) {
                                        return {
                                            id: item[internalSettings.formatters.option.value],
                                            text: Object.byString(item, internalSettings.formatters.option.title),
                                            item: item
                                        }
                                    }),
                                    pagination: {
                                        more: (data.current_page !== data.last_page ? true : false)
                                    }
                                };
                            }
                        }
                    });
                    
                    if($.trim($this.attr('data-value')) !== ""){
                        $this.select2('data', JSON.parse($this.attr('data-value')));
                    }
                    
                    if($.trim($this.attr('data-value')) !== "" && $.trim($this.attr('data-text')) !== ""){
                        $this.append($("<option selected='selected'></option>").val($.trim($this.attr('data-value'))).text($.trim($this.attr('data-text'))));
                    }

                    return;
                }

                if(GLOBALS.options[$this.attr('data-options_source')] !== undefined && GLOBALS.options[$this.attr('data-options_source')] !== null){
                    internalFunctions.load.call($this[0]);
                    return;
                }

                if(internalSettings.options !== null){
                    internalFunctions.response_formatter.call($this[0], internalSettings.options);
                    internalFunctions.load.call($this[0]);
                    return;
                }

                var response = localStorage.getItem("BriskSelectOption-[" + $("meta[name='BASE_URL']").attr("content") + "]-" + $this.attr('data-options_source'));
                
                if(response){
                    console.log("[briskSelectOption][" + $this.attr('data-options_source') + "]: Loading from storage...");

                    internalFunctions.response_formatter.call($this[0], JSON.parse(response));
                    internalFunctions.load.call($this[0]);
                }else{
                    $.ajax({
                        url: internalSettings.resource,
                        type: "GET"
                    })
                    .done(function(response){
                        localStorage.setItem("BriskSelectOption-[" + $("meta[name='BASE_URL']").attr("content") + "]-" + $this.attr('data-options_source'), JSON.stringify(response));

                        internalFunctions.response_formatter.call($this[0], response);
                        internalFunctions.load.call($this[0]);
                    })
                    .fail(function() {
                    })
                    .always(function(){
                    });
                }
            }, 
            response_formatter: function(response) {
                var $this = $(this);
                var internalSettings = $(this).data('briskSelectOptionsSettings');

                if(typeof internalSettings.formatters.response == 'function'){
                    response = internalSettings.formatters.response(response);
                }

                if(GLOBALS.options[$this.attr('data-options_source')] == null){
                    GLOBALS.options[$this.attr('data-options_source')] = {
                        data: response
                    }
                }else{
                    GLOBALS.options[$this.attr('data-options_source')].data = response;
                }

                setTimeout(() => {
                    $(document).trigger('brisk-selectOptions.' + $this.attr('data-options_source') + '.response.loaded');
                }, 500);

                return response; 
            },
            option_formatter: function(key, record) {
                var internalSettings = $(this).data('briskSelectOptionsSettings');

                if(typeof internalSettings.formatters.option == 'function'){
                    return internalSettings.formatters.option(key, record);
                }

                return "<option value=\"" + record[internalSettings.formatters.option.value] + "\">" + record[internalSettings.formatters.option.title] + "</option>"; 
            },
            load: function(){
                var $this = $(this);
                var internalSettings = $(this).data('briskSelectOptionsSettings');
                var response = GLOBALS.options[$this.attr('data-options_source')].data;
                
                if(response.data){
                    response = response.data;
                }
                
                if($this.is('select')){
                    if($.trim($this.html()) !== ""){
                        return;
                    }

                    // console.log($this.attr('name'));
                    
                    if($this.attr('data-has_empty') === 'true'){
                        $this.append("<option></option>");
                    }
    
                    if($this.attr('data-has_all') === 'true'){
                        $this.append("<option value='all'>كافة الخيارات</option>");
                    }
    
                    if($this.attr('data-has_none') === 'true'){
                        $this.append("<option value='none'>لا يوجد</option>");
                    }

                    $.each(response, function(key, record) {
                        $this.append(internalFunctions.option_formatter.call($this[0], key, record));
                    }); 

                    /**
                     * prevents reCalling server for list, when its already has 0 options
                     */
                    if(response.length == 0){
                        var record = [];

                        record[internalSettings.formatters.option.value] = "";
                        record[internalSettings.formatters.option.title] = "";

                        $this.append(internalFunctions.option_formatter.call($this[0], 0, record));
                    }

                    if($this.hasClass('select2')){
                        $this.select2({
                            allowClear: true
                        });
                    }else{
                        if($this.is('[data-placeholder]')){
                            $this.prepend("<option selected disabled>" + $this.attr('data-placeholder') + "</option>");
                        }
                    }

                    if($this.attr('data-value') !== "" && $this.attr('data-value') !== "NaN" && $this.attr('data-value') !== undefined){
                        $this.find('option[value="' + $this.attr('data-value') + '"]').attr('selected', true);
                    }
                }
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
}(jQuery));

Object.byString = function(o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    s = s.replace(/^\./, '');           // strip a leading dot
    var a = s.split('.');
    for (var i = 0, n = a.length; i < n; ++i) {
        var k = a[i];
        if (k in o) {
            o = o[k];
        } else {
            return;
        }
    }
    return o;
}