(function ($) {
    $.fn.briskDataTable = function(argFunction) {
        var baseFunctions = {
            init: function(settings) {
                var defaultColumn = {
                    title: "",
                    column: "",
                    sortable: true,
                    merge: false,
                    formatter: ""
                };

                var defaultInputFilter = {
                    title: "",
                    name: "",
                    type: "input",
                    placeholder: "",
                    classes: [],
                    date: false,
                    data: {}
                };

                var defaultSelectOptionsFilter = {
                    title: "",
                    name: "",
                    type: "select",
                    placeholder: "",
                    classes: [],
                    multiple: false,
                    data: {
                        options_source: "",
                        has_empty: false,
                        has_all: false,
                        has_none: false,
                        value: null,
                        autoload: true
                    }
                };

                var defaultSettings = {
                    language: 'ar', //avaliable languages are ['ar', 'en'], default language is 'ar'
                    direction: "rtl", //['rtl', 'ltr'] and the default is table language direction
                    filters: {
                        title: "أدوات البحث", //Comes from backend JSON by default, and you can override it here
                        classes: [],
                        enable: true, //to show filters panel
                        active: true, //to set filters as Active direct once table loaded
                        initialized: false,
                        resource_additional_filtering: ""
                    },
                    formatters: {},
                    datatable: {
                        title: "", //Comes from backend JSON by default, and you can override it here
                        classes: [],
                        buttons: [],
                        refresh: {
                            enable: true, //to show/hide refresh button. The defualt is true.
                            clear: false, //to clear table results when refresh. The defualt is false. 
                            auto: {
                                active: false, //default is false
                                unit: 'seconds', //['seconds' (default), 'minutes']
                                duration: 5 //default is 5
                            }
                        },
                        order_by: {
                            column: null, //column of first GET call sort
                            method: null //method of first GET call sort
                        },
                        execution_time: true, //to show/hide the execution time of the table
                        tbody: {
                            height: '50vh'
                        },
                        current_page: 1,
                        object: null
                    },
                    resource: {
                        api: 'http://domain-name.com',
                        entity: 'resource',
                        datatable: 'datatable', //required returned JSON format was described in the beginning of README 
                        headers: {}
                    }
                };

                var instanceSettings = $.extend(true, defaultSettings, settings);
                
                instanceSettings.language = $.fn.briskDataTable.languages[instanceSettings.language];

                /**
                 * START:: Refresh Methods Setup
                 */
                // if(instanceSettings.datatable.refresh.enable){
                //     instanceSettings.datatable.buttons.unshift({
                //         data_action: "refresh",
                //         classes: {
                //             button: "btn btn-light btn-xs",
                //             icon: "fas fa-sync-alt"
                //         }
                //     });
                //     $(instanceSettings.datatable.buttons).each(function(key, item){
                //         if(this.title == undefined){
                //             instanceSettings.datatable.buttons[key].title = instanceSettings.language.buttons[instanceSettings.datatable.buttons[key].data_action];
                //         }
                //     });
                // }
    
                if(instanceSettings.datatable.refresh.auto.active){
                    var interval;
    
                    if(instanceSettings.datatable.refresh.auto.unit == "seconds"){
                        interval = instanceSettings.datatable.refresh.auto.duration * 1000;
                    }else if(instanceSettings.datatable.refresh.auto.unit == "minutes"){
                        interval = instanceSettings.datatable.refresh.auto.duration * 1000 * 60;
                    }
    
                    setInterval(function(){
                        internalFunctions.get();
                    }, interval);
                }
                //END:: Refresh Methods Setup

                return this.each(function() {
                    /*
                    * You can reinitialize here if you wish
                    */

                    var $this = $(this);
                    
                    // $(this).addClass('brisk-panel');
                    
                    $this.data('briskDataTableSettings', instanceSettings);
                    $this.data('briskDataTableDefaultColumn', defaultColumn);
                    $this.data('briskDataTableDefaultInputFilter', defaultInputFilter);
                    $this.data('briskDataTableDefaultSelectOptionsFilter', defaultSelectOptionsFilter);

                    /**
                     * START:: HTML Templates Appending
                     */
                    var filters_template_html = '';
                    var filters_switch_template_html = '';
                    var datatable_template_html = '';
                    var datatable_pagination_template_html = '';

                    if(instanceSettings.filters.enable){
                        // $this.find('.brisk-filters').addClass('rtl');

                        if(instanceSettings.filters.active){
                            $this.find('.brisk-filters #filters-status').prop('checked', true);
                        }else{
                            $this.find('.brisk-filters #filters-status').prop('checked', false);
                            $this.find('.brisk-filters').find('.panel-body').fadeOut();
                            internalFunctions.get.call($this[0]);
                        }
                    }

                    /**
                     * START:: Datatable template
                     */
                    if($.trim($this.find('.brisk-datatable').html()) === ""){
                        /*
                        datatable_template_html += '<div class="brisk-datatable panel">';
                        datatable_template_html += '    <div class="panel-heading clearfix">';
                        datatable_template_html += '        <span class="panel-title"></span>';
                        datatable_template_html += '        <div class="panel-heading-controls">';
                        datatable_template_html += '            ';
                        datatable_template_html += '        </div>';
                        datatable_template_html += '    </div>';
                        datatable_template_html += '    <div class="panel-body" style="position: relative;">';
                        datatable_template_html += '        <div class="progress">';
                        datatable_template_html += '            <div class="progress-bar-indeterminate"></div>';
                        datatable_template_html += '         </div>';
                        // datatable_template_html += '        <table class="display nowrap row-border hover order-column" style="width:100%">';
                        datatable_template_html += '        <div class="table-responsive">';
                        datatable_template_html += '            <table class="table table-striped table-hover">';
                        datatable_template_html += '                <thead></thead>';
                        datatable_template_html += '                <tbody></tbody>';
                        datatable_template_html += '            </table>';
                        datatable_template_html += '        </div>';
                        datatable_template_html += '        <div class="footer">';
                        datatable_template_html += '            <div class="right-side"></div>';
                        datatable_template_html += '            <div class="left-side"></div>';
                        datatable_template_html += '        </div>';
                        datatable_template_html += '    </div>';
                        datatable_template_html += '</div>';
                        */

                        datatable_template_html += '<div class="card mb-3" brisk-datatable>';
                        datatable_template_html += '    <div class="card-header">';
                        datatable_template_html += '        <div class="row align-items-center justify-content-between">';
                        datatable_template_html += '            <div class="col-4 col-sm-auto d-flex align-items-center pr-0">';
                        datatable_template_html += '                <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0" brisk-datatable-title></h5>';
                        datatable_template_html += '            </div>';
                        datatable_template_html += '            <div class="col-8 col-sm-auto ml-auto text-right pl-0">';
                        datatable_template_html += '                <div class="d-none" id="orders-actions">';
                        datatable_template_html += '                    <div class="input-group input-group-sm">';
                        datatable_template_html += '                        <select class="custom-select cus" aria-label="Bulk actions">';
                        datatable_template_html += '                            <option selected="">Bulk actions</option>';
                        datatable_template_html += '                            <option value="Refund">Refund</option>';
                        datatable_template_html += '                            <option value="Delete">Delete</option>';
                        datatable_template_html += '                            <option value="Archive">Archive</option>';
                        datatable_template_html += '                        </select>';
                        datatable_template_html += '                <button class="btn btn-falcon-default btn-sm ml-2" type="button">Apply</button>';
                        datatable_template_html += '             </div>';
                        datatable_template_html += '            </div>';
                        datatable_template_html += '            <div id="dashboard-actions" brisk-datatable-actions>';
                        // datatable_template_html += '                <button class="btn btn-falcon-default btn-sm" type="button" data-action="create"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span class="d-none d-sm-inline-block ml-1">New</span></button>';
                        // datatable_template_html += '                <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt" data-fa-transform="shrink-3 down-2"></span><span class="d-none d-sm-inline-block ml-1">Export</span></button>';
                        datatable_template_html += '                <button class="btn btn-falcon-default btn-sm" type="button" data-action="filters"><span class="fas fa-filter"></span></button>';
                        datatable_template_html += '                <button class="btn btn-falcon-default btn-sm" type="button" data-action="refresh"><span class="fas fa-sync"></span></button>';
                        datatable_template_html += '                <div class="dropdown text-sans-serif d-inline-block mb-2" brisk-datatable-pagination-per-page>';
                        datatable_template_html += '                   <button class="btn btn-falcon-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">10</button>';
                        datatable_template_html += '                    <div class="dropdown-menu dropdown-menu-right py-0">';
                        datatable_template_html += '                        <a class="dropdown-item active">10</a>';
                        datatable_template_html += '                        <a class="dropdown-item">25</a>';
                        datatable_template_html += '                        <a class="dropdown-item">50</a>';
                        datatable_template_html += '                        <a class="dropdown-item">100</a>';
                        datatable_template_html += '                        <a class="dropdown-item">200</a>';
                        datatable_template_html += '                    </div>';
                        datatable_template_html += '                </div>';
                        datatable_template_html += '            </div>';
                        datatable_template_html += '        </div>';
                        datatable_template_html += '    </div>';
                        datatable_template_html += '</div>';

                        datatable_template_html += '<div class="card-body p-0">';
                        datatable_template_html += '    <form class="px-3 pt-2 pb-0 d-none" brisk-datatable-filters></form>';
                        datatable_template_html += '        <div class="progress">';
                        datatable_template_html += '            <div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>';
                        datatable_template_html += '        </div>';
                        datatable_template_html += '    <div class="falcon-data-table table-responsive">';
                        datatable_template_html += '        <table class="table table-sm mb-0 table-striped table-dashboard fs--1 border-bottom border-200 table-hover">';
                        datatable_template_html += '            <thead class="bg-200 text-900"></thead>';
                        datatable_template_html += '            <tbody></tbody>';
                        datatable_template_html += '        </table>';
                        datatable_template_html += '    </div>';
                        datatable_template_html += '</div>';
                        datatable_template_html += '<div class="card-footer row mx-1 align-items-center justify-content-center justify-content-md-between p-0">';
                        datatable_template_html += '    <div class="col-auto mb-2 mb-sm-0 fs--1 pt-2" brisk-datatable-results-count></div>';
                        datatable_template_html += '    <div class="col-auto d-none" brisk-datatable-pagination>';
                        datatable_template_html += '        <nav class="mt-3">';
                        datatable_template_html += '            <ul class="pagination pagination-sm"></ul>';
                        datatable_template_html += '        </nav>';
                        datatable_template_html += '    </div>';
                        datatable_template_html += '</div>';
                        datatable_template_html += '</div>';

                        //Inject Template HTML
                        $(datatable_template_html).appendTo(this);
                    }
                    //END

                    // $this.find('.brisk-datatable').addClass(instanceSettings.direction);

                    if(!instanceSettings.filters.enable){
                        $this.find('[data-action="filters"]').remove();
                        $this.find('[brisk-datatable-filters]').remove();
                    }

                    if(instanceSettings.datatable.tbody.height === defaultSettings.datatable.tbody.height && !instanceSettings.filters.enable){
                        $this.find('[brisk-datatable] .card-body').css({'min-height': '60vh'});
                    }else{
                        $this.find('[brisk-datatable] .card-body').css({'min-height': instanceSettings.datatable.tbody.height});
                    }

                    $this.find('[brisk-datatable-title]').html(instanceSettings.language.datatable.title);

                    $.each(instanceSettings.datatable.buttons, function(key, button){
                        var data_attributes = '';

                        if(button.data){
                            $.each(button.data, function(key, value){
                                data_attributes += ' data-' + key + "='" + value + "'";
                            });
                        }

                        $this.find('[brisk-datatable-actions]').prepend('<button class="' + button.classes.button + '" type="button" data-action="' + button.data_action + '"' + ($.trim(data_attributes) !== "" ? $.trim(data_attributes) : "") + '><span class="' + button.classes.icon + '" data-fa-transform="shrink-3 down-1"></span><span class="d-none d-sm-inline-block ml-1">' + button.title + '</span></button>');
                    });

                    // $.each(instanceSettings.datatable.classes, function(key, class_title){
                    //     $this.find('.brisk-datatable').addClass(class_title);
                    // });
                    //END:: HTML Templates Appending

                    /**
                     * START:: Events Init.
                     */
                    $this.find('button[data-action="refresh"]').on("click.internalFunctions", function(){
                        internalFunctions.get.call($this[0]);
                    });

                    $this.find('[brisk-datatable-pagination-per-page] a').on('click', function(){
                        $this.find('[brisk-datatable-pagination-per-page] a').removeClass('active');
                        $(this).addClass('active');
                        $this.find('[brisk-datatable-pagination-per-page] button').html($.trim($(this).html()));
                        $this.find('button[data-action="refresh"]').trigger('click');
                    });

                    $this.on('click.internalFunctions', '[brisk-datatable-pagination] [data-page-id]', function(){
                        instanceSettings.datatable.current_page = $(this).attr('data-page-id');
                        $this.find('button[data-action="refresh"]').trigger('click');
                    });
                    
                    $this.on('click.internalFunctions', '[data-action="filters"]', function(){
                        if($(this).hasClass('active')){
                            instanceSettings.filters.active = false;
                            $(this).removeClass('active');
                        }else{
                            instanceSettings.filters.active = true;
                            $(this).addClass('active');
                        }

                        if(instanceSettings.filters.active){
                            $this.find('[brisk-datatable-filters]').removeClass('d-none');
                            localStorage.setItem("BriskDataTable-[" + $("meta[name='BASE_URL']").attr("content") + "]-filters", "active");
                        }else{
                            $this.find('[brisk-datatable-filters]').addClass('d-none');
                            $this.find('button[data-action="refresh"]').trigger('click');
                            localStorage.removeItem("BriskDataTable-[" + $("meta[name='BASE_URL']").attr("content") + "]-filters");
                        }
                    }); 

                    $this.on('submit', '[brisk-datatable-filters]', function(event){
                        event.preventDefault();
                    });

                    $this.on('change.internalFunctions', '[brisk-datatable-filters] input:not([date-range-filter]), [brisk-datatable-filters] select', function(event){
                        $this.find('button[data-action="refresh"]').trigger('click');
                    });

                    $this.on('change.internalFunctions', '[brisk-datatable-filters] input[date-range-filter]', function(event){
                        if($.trim(this.value) !== "" && $(document).find('.flatpickr-calendar').is('.open')){
                            return;
                        }
                        
                        $this.find('button[data-action="refresh"]').trigger('click');
                    });

                    /*
                    $datatableElement.on('click.internalFunctions', 'table thead tr th[data-sort]', function(){
                        internalFunctions.orderBy.call($this[0], $(this).data('sort'));
                        internalFunctions.get.call($this[0]);
                    }); 
                    */
                    //END:: Events Init.

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
                
                var internalSettings = $this.data('briskDataTableSettings');

                internalFunctions.loading.call(this);

                var URL = internalSettings.resource.api + "/" + internalSettings.resource.entity + "/" + internalSettings.resource.datatable;

                URL += "?page=" + internalSettings.datatable.current_page;

                URL += "&per_page=" + $this.find('[brisk-datatable-pagination-per-page] .active').html();

                if(internalSettings.datatable.order_by.column !== null){
                    URL += "&order_by_column=" + internalSettings.datatable.order_by.column;
                }
                if(internalSettings.datatable.order_by.method !== null){
                    URL += "&order_by_method=" + internalSettings.datatable.order_by.method;
                }
                
                if(internalSettings.filters.enable){
                    URL += "&filters_status=" + Number($this.find('[data-action="filters"]').hasClass('active'));

                    if(internalSettings.filters.active){
                        $(this).find('[brisk-datatable-filters] [name]').each(function() {
                            if($(this).val() == null || $(this).val() == undefined || $.trim($(this).val()) == ""){
                                return true;
                            }

                            URL += "&" + $.trim($(this).attr('name')) + "=" + $.trim($(this).val());
                        }); 
                    }
                }

                if($.trim(internalSettings.filters.resource_additional_filtering) !== ""){
                    URL += "&" + internalSettings.filters.resource_additional_filtering;
                }

                var $refreshButton = $this.find('button[data-action="refresh"]');
                $refreshButton.html('<div class="spinner-border spinner-grow-sm" role="status"><span class="sr-only">Loading...</span></div>');
                $refreshButton.prop('disabled', true);

                $this.find('.progress-bar').fadeIn();
            
                var ajaxTime = new Date().getTime();

                var xhr = $this.data('briskDataTableXHR');

                if(xhr) xhr.abort();

                $this.data('briskDataTableXHR', $.ajax({
                    url: URL,
                    type: "GET",
                    headers: internalSettings.resource.headers
                })
                .done(function(response){
                    internalFunctions.setFilters.call($this[0], response.filters);
                    internalFunctions.setColumns.call($this[0], response.columns);
                    internalFunctions.setTitles.call($this[0], response);

                    var dataSet = [];
                    
                    $.each(response.data, function(key, record) {
                        dataSet.push(internalFunctions.rows_reformat.call($this[0], response.columns, key, record));
                    });
                    
                    $this.find('tbody').html("");

                    var rows = '';

                    $(dataSet).each(function(key, columns){
                        rows += '<tr class="btn-reveal-trigger">';
                        // rows += '    <td class="py-2 align-middle">';
                        // rows += '        <div class="custom-control custom-checkbox">';
                        // rows += '            <input class="custom-control-input checkbox-bulk-select-target" type="checkbox" id="checkbox-0" />';
                        // rows += '            <label class="custom-control-label" for="checkbox-0"></label>';
                        // rows += '        </div>';
                        // rows += '    </td>';

                        $(columns).each(function(key, value){
                            var column_title = (response.columns[key].column ? $.trim(response.columns[key].column) : "");
                            rows += '<td class="py-2 align-middle" data-column-title="' + column_title + '">' + value + '</td>';
                        });
                        
                        rows += '</tr>';
                    });

                    if(!dataSet.length){
                        rows += '<tr>';
                        rows += '    <td colspan="100" class="py-2 align-middle text-center">' + internalSettings.language.results.not_found + '</td>';
                        rows += '</tr>';
                    }

                    $this.find('tbody').append(rows);

                    $this.find('[brisk-datatable-results-count]').html(internalFunctions.setFooter.call($this[0], response.total, (response.data == undefined ? 0 : response.data.length), response.footer));

                    if(internalSettings.datatable.execution_time){
                        var execution_time = new Date(null);
                        execution_time.setTime(new Date().getTime() - ajaxTime);

                        var execution_time_info = internalSettings.language.execution_time[0] + ": <span>" + execution_time.getSeconds() + "." + execution_time.getMilliseconds() + "</span> " + internalSettings.language.execution_time[1] + ".";
                        $this.find('[brisk-datatable-results-count]').append(execution_time_info);
                    }
                    
                    internalFunctions.setPagination.call($this[0], response.current_page, response.last_page);
                    internalFunctions.initDestroy.call($this[0]);

                    $this.trigger('briskDataTable.get.done', response);
                })
                .fail(function() {
                    console.log("BriskDataTable::get(): HTTP Call Failure!");
                })
                .always(function(){              
                    $refreshButton.html('<span class="fas fa-sync"></span>');
                    $refreshButton.prop('disabled', false);
                    $this.find('.progress-bar').fadeOut();
                }));
            },
            setFilters: function(filters) {    
                var $this = $(this);
                var internalSettings = $(this).data('briskDataTableSettings');
                var briskDataTableDefaultInputFilter = $(this).data('briskDataTableDefaultInputFilter');
                var briskDataTableDefaultSelectOptionsFilter = $(this).data('briskDataTableDefaultSelectOptionsFilter');

                if(!internalSettings.filters.enable){
                    return;
                }

                if(internalSettings.filters.initialized){
                    return;
                }

                internalSettings.filters.initialized = true;

                /**
                 * START:: Filters Appending
                 */
                if(filters == undefined){
                    filters = [];
                }

                var filters_html = '';

                filters_html += '<div class="row">';

                $.each(filters, function(key, filter) {
                    if(filter.type == "input"){
                        filter = $.extend({}, briskDataTableDefaultInputFilter, filter);
                    }

                    if(filter.type == "select"){
                        filter = $.extend({}, briskDataTableDefaultSelectOptionsFilter, filter);
                    }

                    var filter_default_classes = ['form-control'];

                    filters_html += '<div class="col-lg-3 col-md-3 col-sm-4 mb-3">';
                    filters_html += '   <label class="input-group">' + filter.title + '</label>';
                    filters_html += '   <' + filter.type + ' name="' + filter.name + '" autocomplete="off" ';

                    /**
                     * START:: Classes Init.
                     */
                    $.each(filter.classes, function(filter_key, filter_class){
                        filter_default_classes.push(filter_class);
                    });

                    if(filter.date || filter.date_range){
                        filter_default_classes.push('text-center');
                    }

                    filters_html += ' class="';
                    $.each(filter_default_classes, function(filter_key, filter_class){
                        filters_html += filter_class + ' ';
                    });
                    filters_html += '"';
                    //END:: Classes Init.

                    filters_html += ' placeholder="' + filter.placeholder + '"';

                    $.each(filter.data, function(key, attribute){
                        filters_html += ' data-' + key + '="' + attribute + '"';
                    });

                    if(filter.date){
                        filters_html += ' date-filter';
                    }

                    if(filter.date_range){
                        filters_html += ' data-options=\'{"mode":"range"}\' date-range-filter';
                    }

                    if(filter.type === "select"){
                        if(Boolean(filter.multiple)){
                            filters_html += ' multiple="multiple" ';
                        }
                        filters_html += '   >';
                        filters_html += '   </' + filter.type + '>';
                    }else{
                        filters_html += '   />';
                    }
                    filters_html += '</div>';
                });

                filters_html += "</div>"

                $this.find('[brisk-datatable-filters]').html(filters_html);
                $this.find('[brisk-datatable-filters] select').select2();
                //END:: Filters Appending

                /**
                 * START:: Filters Init.
                 */
                /*
                $this.find('.brisk-filters .masked-input-date').datepicker({
                    todayBtn: "linked",
                    isRTL: $this.find('.brisk-datatable').hasClass('rtl') ? true : false
                });
                */

                initDatetimepicker($this.find('[date-filter]'));
                initDatetimepicker($this.find('[date-range-filter]'));

                $this.find('[data-options_source]').each(function(){
                    if(GLOBALS.lists[$(this).attr('data-options_source')]){
                        GLOBALS.lists[$(this).attr('data-options_source')]($(this));
                    }
                });

                if(localStorage.getItem("BriskDataTable-[" + $("meta[name='BASE_URL']").attr("content") + "]-filters") == "active"){
                    $this.find('[data-action="filters"]').trigger('click');
                }

                //END:: Filters Init.

                $this.trigger('briskDataTable.filters.build.done');
            },
            setColumns: function(columns) {                
                var internalSettings = $(this).data('briskDataTableSettings');
                var briskDataTableDefaultColumn = $(this).data('briskDataTableDefaultColumn');

                var datatable = internalSettings.datatable;
                var html = "<tr>";
                
                // html += '<th class="align-middle no-sort">';
                // html += '    <div class="custom-control custom-checkbox">';
                // html += '       <input class="custom-control-input checkbox-bulk-select" id="checkbox-bulk-purchases-select" type="checkbox" data-checkbox-body="#orders" data-checkbox-actions="#orders-actions" data-checkbox-replaced-element="#dashboard-actions">';
                // html += '       <label class="custom-control-label" for="checkbox-bulk-purchases-select"></label>';
                // html += '    </div>';
                // html += '</th>';

                $.each(columns, function(key, column) {
                    column = $.extend({}, briskDataTableDefaultColumn, column);

                    html += "<th class='align-middle sort'";

                    if(column.sortable){
                        html += " data-sort='" + column.column + "'";
                    }
        
                    html += ">";
        
                    /*
                    if(column.sortable){
                        if(datatable.order_by.column == column.column){
                            if(datatable.order_by.method == "ASC"){
                                if(internalSettings.language.direction === "ltr"){
                                    html += "<i class='fa fa-angle-down' style='float: right;'></i>";                
                                }else{
                                    html += "<i class='fa fa-angle-down' style='float: left;'></i>"; 
                                }
                            }else{
                                if(internalSettings.language.direction === "ltr"){
                                    html += "<i class='fa fa-angle-up' style='float: right;'></i>";
                                }else{
                                    html += "<i class='fa fa-angle-up' style='float: left;'></i>";
                                }                
                            }
                        }    
                    }
                    */
                        
                    html += column.title + "</th>";
                });
                
                html += "</tr>";

                $(this).find('table thead').html(html);

                // /**
                //  * Show/hide columns dynamically
                //  */
                // var html = '';

                // html += '<div class="btn-group columns">';
                // html += '    <button type="button" class="btn btn-xs">إخفاء/عرض الأعمدة</button>';
                // html += '    <ul class="dropdown-menu">';
                // $.each(columns, function(key, column) {
                //     html += '        <li><a data-column="' + key + '">' + column.title + '</a></li>';
                // });
                // html += '    </ul>';
                // html += '</div>';

                // $(this).find('.brisk-datatable .panel-heading-controls').append(html);
            },
            setPagination: function(current_page, last_page) {
                var internalSettings = $(this).data('briskDataTableSettings');
                
                var html = "";
                html += '<li class="page-item" title="الصفحة الأولى"><a class="page-link" data-page-id="1" aria-label="الصفحة الأولى"><span aria-hidden="true">&laquo;</span><span class="sr-only">الصفحة الأولى</span></a></li>';
            
                if(current_page !== 1){
                    html += '<li class="page-item"><a class="page-link" data-page-id="' + (current_page - 1) + '">' + (current_page - 1) + '</a></li>';
                }
            
                html += '<li class="page-item active"><a class="page-link" data-page-id="' + current_page + '">' + current_page + '</a></li>';
            
                if(current_page !== last_page && last_page !== 0){
                    html += '<li class="page-item"><a class="page-link" data-page-id="' + (current_page + 1) + '">' + (current_page + 1) + '</a></li>';
                }
            
                html += '<li class="page-item" title="الصفحة الأخيرة"><a class="page-link" data-page-id="' + last_page + '" aria-label="الصفحة الأخيرة"><span aria-hidden="true">&raquo;</span><span class="sr-only">الصفحة الأخيرة</span></a></li>';

                $(this).find('[brisk-datatable-pagination] ul').html(html);
                $(this).find('[brisk-datatable-pagination]').removeClass("d-none");
            },
            setTitles: function(response) {
                var internalSettings = $(this).data('briskDataTableSettings');

                //filters panel title
                if($.trim(internalSettings.filters.title) == ""){
                    var filters_title;

                    if(internalSettings.filters.title !== ""){
                        filters_title = internalSettings.filters.title;
                    }else if($.trim(response.filters_title) !== ""){
                        filters_title = response.filters_title;
                    }else{
                        filters_title = internalSettings.language.filters.title;
                    }

                    // internalSettings.filters.element.find('.panel-title').html(filters_title);
                    $(this).find('.brisk-filters .panel-title').html(response.table_title);
                }
                //table panel title
                if($.trim(internalSettings.datatable.title) == ""){
                    var table_title;

                    if(internalSettings.datatable.title !== ""){
                        table_title = internalSettings.datatable.title;
                    }else if($.trim(response.table_title) !== ""){
                        table_title = response.table_title;
                    }else{
                        table_title = internalSettings.language.datatable.title;
                    }

                    // internalSettings.datatable.element.find('.panel-title').html(response.table_title);
                    $(this).find('.brisk-datatable .panel-title').html(response.table_title);
                }
            },
            rows_reformat: function(columns, key, record) {
                var internalSettings = $(this).data('briskDataTableSettings');

                var row = []; 

                $.each(columns, function(column_key, column){
                    var value = $.fn.briskDataTable.nested_columns_access(column, record);

                    if(column.formatter) {
                        if(typeof internalSettings.formatters[column.formatter] == "function"){
                            value = internalSettings.formatters[column.formatter].call(this, record, column.column);
                        }
                        
                        if(internalSettings.formatters[column.formatter] !== undefined){
                            if(typeof internalSettings.formatters[column.formatter].render == "function"){
                                value = internalSettings.formatters[column.formatter].render.call(this, record, column.column);
                            } 
                            if(typeof internalSettings.formatters[column.formatter].editable == "function"){
                                value = internalSettings.formatters[column.formatter].editable.call(this, record, column.column, value);
                            }
                        }
                    }

                    row.push(value == null ? '-' : value);
                });

                return row;
            },
            setFooter: function(total, viewed, footer) {
                var internalSettings = $(this).data('briskDataTableSettings');

                var info = "";
        
                if(!viewed){
                    info = internalSettings.language.results.not_found + ". ";
                }else{
                    info = internalSettings.language.results.found[0] + " <span>" + viewed + "</span> " + internalSettings.language.results.found[1] + " " + total + " " + internalSettings.language.results.found[2] + ".";
                    
                    info += "<span class='info'>";
                    
                    if(footer.info !== undefined){
                        info += footer.info;
                    }
        
                    info += "</span> ";
                }

                return info;
            },
            loading: function() {
                var internalSettings = $(this).data('briskDataTableSettings');

                var row = "";
        
                if(!internalSettings.datatable.refresh.clear){
                    row += internalSettings.language.results.refresh;
        
                    $(this).find('table tbody tr.footer span.info').html(row);
                }else{
                    $(this).find('table tbody').html("");
                    
                    row = "<tr>";
                    row += "    <td class='rows-alternative' colspan='100'>" + internalSettings.language.results.refresh + "</td>";
                    row += "</tr>";
        
                    $(this).find('table tbody').append(row);
                }
            },
            orderBy: function(column){
                var internalSettings = $(this).data('briskDataTableSettings');

                if(internalSettings.datatable.order_by.column == column){
                    if(internalSettings.datatable.order_by.method == "ASC"){
                        internalSettings.datatable.order_by.method = "DESC";
                        return;
                    }
        
                    internalSettings.datatable.order_by.method = "ASC";
                }else{
                    internalSettings.datatable.order_by.column = column;
                    internalSettings.datatable.order_by.method = "ASC";
                }
                
                $(this).find('table thead tr th[data-sort="' + column + '"] i.fa').removeClass("fa-angle-up").addClass("fa-angle-down"); 
            },
            initDestroy: function() {
                var $datatable = $(this);
                var internalSettings = $(this).data('briskDataTableSettings');

                $(this).find("[data-action='destroy']").each(function(key, element){
                    $(element).bind('click', function(){
                        var $this = $(this);
        
                        swal({
                            title: "هل أنت متأكد من أنك تريد حذف البيانات؟",
                            icon: "warning",
                            dangerMode: true,
                            buttons: ["إلغاء العملية", "حذف البيانات"]
                        })
                        .then((process) => {
                            if (!process) {
                                return;
                            }
        
                            var buttonText = $this.html();
                            $this.attr('disabled', true);
                            $this.html('<span class="fa fa-spinner"></span>');

                            $.ajax({
                                url: internalSettings.resource.api + "/" + internalSettings.resource.entity + "/" + $this.attr('data-id'),
                                type: 'DELETE',
                                data: $.fn.briskDataTable.defaults.destroy.request.data,
                                success: function(response) {
                                    $("#" + $datatable.attr('id')).briskDataTable('refresh');
                                    http.success(response);
                                },
                                error: function(response) {
                                    http.fail(response.responseJSON, true);
                                },
                                complete: function(response) {
                                    $this.attr('disabled', false);
                                    $this.html(buttonText);
                                }
                            });
                        });
                    });
                });
            },
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

    $.fn.briskDataTable.nested_columns_access = function(column, record){
        var columns = column.column.split('.');

        if(column.merge){
            /*
            for(counter = 0; counter < column.column.split('.').length - 2; counter++){
                    record = record[columns[counter]];
            }

            var results = [];
            $(record).each(function(){
                results.push(this[columns[counter]][columns[column.column.split('.').length - 1]]);
            });
            
            record = results;
            */
            
            if(column.column == 'categories.category.name'){
                console.log(true);
            }

            for(counter = 0; counter < column.column.split('.').length - 1; counter++){
                if(record !== null)
                    record = record[columns[counter]];

                if(column.column == 'categories.category.name'){
                    console.log(record);
                }
            }
            

            var results = [];
            $(record).each(function(){
                results.push(this[columns[column.column.split('.').length - 1]]);
            });
            
            record = results;
        }else{
            $(columns).each(function(){
                if(record !== undefined && record !== null)
                    record = record[this];
            });
        }
        
        return record;
    };

    $.fn.briskDataTable.languages = {
        ar: {
            code: "ar",
            direction: "rtl",
            filters: {
                title: "أدوات البحث"
            },
            datatable: {
                title: "النتائج"
            },
            buttons: {
                refresh: "",
                insert: "جديد",
                create: "جديد"
            },
            execution_time: {
                0: "استغرقت العملية",
                1: "ثانية"
            },
            results: {
                refresh: "جاري تحديث البيانات...",
                not_found: "لم يتم العثور على نتائج",
                found: {
                    0: "تم عرض",
                    1: "من أصل",
                    2: "نتيجة"
                }
            }
        },
        en: {
            code: "en",
            direction: "ltr",
            filters: {
                title: "Filtering Tools"
            },
            datatable: {
                title: "Results"
            },
            buttons: {
                refresh: "",
                insert: "New record"
            },
            execution_time: {
                0: "Rendered in",
                1: "second"
            },
            results: {
                refresh: "Loading data...",
                not_found: "No results found",
                found: {
                    0: "We found",
                    1: "of results"
                }
            }
        }
    };

    $.fn.briskDataTable.defaults = {
        destroy: {
            request: {
                data: {
                    _token: $("meta[name='csrf-token']").attr("content")
                }
            }
        }
    }
}(jQuery));