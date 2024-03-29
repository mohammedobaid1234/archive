<nav class="navbar navbar-vertical navbar-expand-xl navbar-light">
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-toggle="tooltip" data-placement="right" title="عرض/إخفاء القائمة الرئيسية"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
        </div>
        <a class="navbar-brand" href="{{ url('/') }}/dashboard">
            <div class="d-flex align-items-center py-3">
                <img class="mr-2" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}" alt="" width="40" />
                <span class="text-stc- fs-1" dir="ltr">{{ config('app.name', 'AMTC Portal') }}</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content perfect-scrollbar scrollbar pt-0">
            <div class="p-1" style="padding-top: 0px !important">
                <input type="text" class="form-control mb-1" placeholder="البحث في القائمة..." data-action="navbar-filter" />
            </div>
            <ul class="navbar-nav flex-column">
                <li class="pt-0 pb-2 px-2">
                    <div class="media align-items-center mb-1 employee">
                        <img class="rounded-circle" src="{{ \Auth::user()->userable->personal_image_url }}" alt="{{ \Auth::user()->userable->full_name }}" width="50" />
                        <div class="media-body ml-3">
                            <h5 class="mb-0 font-weight-semi-bold text-900">{{ \Auth::user()->userable->first_name }} {{ \Auth::user()->userable->last_name }}</h5>
                            @foreach (\Auth::user()->roles as $role)
                                <span class="badge badge-soft-info fs--3 mb-0">{{ $role->label }}</span>
                            @endforeach
                        </div>
                    </div>
                </li>
                {{-- <li class="text-center"> --}}
                    {{-- <label class="mb-0 d-block fs--2">أحدث تسجيل دخول</label> --}}
                    {{-- <span class="fs--2">{{ (\Auth::user()->previousLoginAt() ? \Auth::user()->previousLoginAt() : '(أول مرة لتسجيل دخول)') }}</span> --}}
                {{-- </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}/dashboard">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                            <span class="nav-link-text">{{ __('الرئيسية') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <div class="d-flex align-items-center" data-action="change-password-create">
                            <span class="nav-link-icon"><span class="fas fa-cog"></span></span>
                            <span class="nav-link-text">{{ __('إعادة تعيين كلمة المرور') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <div class="d-flex align-items-center" data-action="logout">
                            <span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span>
                            <span class="nav-link-text">{{ __('تسجيل الخروج') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
                
            </div>
             <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(
                    \Auth::user()->can('products_module_customers_manage') ||
                    \Auth::user()->can('products_module_attributes_manage') ||
                    \Auth::user()->can('products_module_products_manage')
                )
                    @if(\Auth::user()->can('products_module_categories_manage'))
                        <li class="nav-item @if(isset($activePage['categories'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/categories/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-sitemap"></span></span>
                                    <span class="nav-link-text">إدارة التصنيفات</span>
                                </div>
                            </a>
                        </li>
                    @endif


                    @if(\Auth::user()->can('products_module_products_manage'))
                        <li class="nav-item @if(isset($activePage['products'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/products/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة المنتجات</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    @endif
                    {{--  
                    @if(\Auth::user()->can('products_module_carts_manage'))
                        <li class="nav-item @if(isset($activePage['carts'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/products/carts/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                                    <span class="nav-link-text">إدارة سلة المشتريات</span>
                                </div>
                            </a>
                        </li>
                    @endif

                    @if(\Auth::user()->can('products_module_wishlists_manage'))
                        <li class="nav-item @if(isset($activePage['wishlists'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/products/wishlists/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-heart"></span></span>
                                    <span class="nav-link-text">إدارة المفضلة</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->can('products_module_products_view_manage'))
                        <li class="nav-item @if(isset($activePage['products_views'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/products/views/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة مشاهدات المنتجات</span>
                                </div>
                            </a>
                        </li>
                    @endif

                @endif
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>

            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(
                    \Auth::user()->can('customers_module_customers_manage') ||
                    \Auth::user()->can('employees_module_employees_manage')
                )
                    @if(\Auth::user()->can('customers_module_customers_manage'))
                        <li class="nav-item @if(isset($activePage['customers'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/customers/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                    <span class="nav-link-text">ملفات العملاء</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->can('employees_module_employees_manage'))
                        <li class="nav-item @if(isset($activePage['employees'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/employees/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                    <span class="nav-link-text">ملفات الموظفين</span>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(\Auth::user()->can('employees_module_employees_manage'))
                    @if(\Auth::user()->can('employees_module_employees_manage'))
                        <li class="nav-item @if(isset($activePage['inquiries'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/inquiries/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">متابعة الاستفسارات</span>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
             --}}
             <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator" href="#archive" data-toggle="collapse" role="button" @if(isset($activePage['contract'])
                        || isset($activePage['sales_invoices_without_cart']) 
                        || isset($activePage['sales_invoices'])   
                        || isset($activePage['receipt_statements']))
                         aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-archive"></span></span>
                                <span class="nav-link-text">{{ __('الأرشيف ') }}</span>
                            </div>
                        </a>
                        <ul class="nav collapse @if(isset($activePage['archive'])) show @endif" id="archive" data-parent="#navbarVerticalCollapse">
                                @if(\Auth::user()->can('contracts_module_contracts_manage'))
                                @if(\Auth::user()->can('contracts_module_contracts_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])  && $activePage['archive'] == 'contracts') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/contracts/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف العقود </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
            
                            @if(\Auth::user()->can('customers_module_receipt_statements_manage'))
                                @if(\Auth::user()->can('customers_module_receipt_statements_manage'))
                                    <li class="nav-item @if(isset($activePage['archive']) && $activePage['archive'] == 'receipt_statements') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/receipt_statements/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف إيصالات القيض </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('customers_module_sales_invoices_manage'))
                                @if(\Auth::user()->can('customers_module_sales_invoices_manage'))
                                    <li class="nav-item @if(isset($activePage['archive']) && $activePage['archive'] == 'sales_invoices') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/sales_invoices/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف المبيعات </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('customers_module_sales_invoices_without_cart_manage'))
                                @if(\Auth::user()->can('customers_module_sales_invoices_without_cart_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])&& $activePage['archive'] == 'sales_invoices_without_cart') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/sales_invoices_without_carts/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف فواتير البيع </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('customers_module_checks_manage'))
                                @if(\Auth::user()->can('customers_module_checks_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])&& $activePage['archive'] == 'checks') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/checks/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف  الشيكات </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('customers_module_drafts_manage'))
                                @if(\Auth::user()->can('customers_module_drafts_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])&& $activePage['archive'] == 'drafts') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/drafts/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">أرشيف  الكمبيالات </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('expenses_module_exchange_bonds_manage'))
                                @if(\Auth::user()->can('expenses_module_exchange_bonds_manage'))
                                    <li class="nav-item @if(isset($activePage['archive']) && $activePage['archive'] == 'exchange_bonds') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/exchangeBonds/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">سندات  الصرف </span>
                                            </div>
                                        </a>
                                    </li>
                                    
                                @endif
                            @endif
                            @if(\Auth::user()->can('core_module_electricities_manage'))
                                @if(\Auth::user()->can('core_module_electricities_manage'))
                                    <li class="nav-item @if(isset($activePage['archive']) && $activePage['archive'] == 'electricities') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/electricities/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">سحب الكهرباء </span>
                                            </div>
                                        </a>
                                    </li>
                                    
                                @endif
                            @endif
                            @if(\Auth::user()->can('expenses_module_expenses_manage'))
                                @if(\Auth::user()->can('expenses_module_expenses_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])&& $activePage['archive'] == 'expenses') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/expenses/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">المصروفات </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('customers_module_customer_payments_dates_manage'))
                                @if(\Auth::user()->can('customers_module_customer_payments_dates_manage'))
                                    <li class="nav-item @if(isset($activePage['archive'])&& $activePage['archive'] == 'customer_payments_dates') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/customerPaymentsDates/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-file-contract"></span></span>
                                                <span class="nav-link-text">دفعات الزبائن</span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(\Auth::user()->can('expenses_module_other_papers_manage'))
                                @if(\Auth::user()->can('expenses_module_other_papers_manage'))
                                    <li class="nav-item @if(isset($activePage['archive']) &&$activePage['archive'] == 'other_papers') active @endif">
                                        <a class="nav-link" href="{{ url('/') }}/otherPapers/manage">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-file-contract"></span></span>
                                                <span class="nav-link-text">أوراق أخرى </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </li>
             </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            {{-- 

            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(\Auth::user()->can('core_module_notifications_manage'))
                    <li class="nav-item @if(isset($activePage['notifications'])) active @endif">
                        <a class="nav-link" href="{{ url('/') }}/notifications/manage">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                <span class="nav-link-text">إدارة الاشعارات</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(\Auth::user()->can('core_module_notification_types_manage'))
                    <li class="nav-item @if(isset($activePage['notificationsTypes'])) active @endif">
                        <a class="nav-link" href="{{ url('/') }}/notifications/types/manage">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                <span class="nav-link-text">إدارة أنواع الاشعارات</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>--}}
             
                <ul class="navbar-nav flex-column" id="navbarVerticalNav1">
                    @if(
                        \Auth::user()->can('customers_module_customers_manage') ||
                        \Auth::user()->can('employees_module_employees_manage')
                    )
                        @if(
                        \Auth::user()->can('cars_module_cars_manage')
                        )
                            <ul class="navbar-nav flex-column" id="navbarVerticalNav1">
                                @if(\Auth::user()->can('users_module_users_manage'))
                                    <li class="nav-item">
                                        <a class="nav-link dropdown-indicator" href="#cars" data-toggle="collapse" role="button" @if(isset($activePage['cars'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">{{ __('إدارة السيارات') }}</span>
                                            </div>
                                        </a>
                                        <ul class="nav collapse @if(isset($activePage['cars'])) show @endif" id="cars" data-parent="#navbarVerticalCollapse">
                                            <li class="nav-item @if(isset($activePage['cars']) && $activePage['cars'] == 'cars') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/cars/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-car"></span></span>
                                                        <span class="nav-link-text">ملفات السيارات</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['cars']) && $activePage['cars'] == 'cars_papers') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/carsPapers/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-shield-alt"></span></span>
                                                        <span class="nav-link-text">ملفات اوراق السيارات</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['cars']) && $activePage['cars'] == 'cars_maintenance') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/carsMaintenances/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-wrench"></span></span>
                                                        <span class="nav-link-text">ملفات صيانة السيارات</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['cars'])&& $activePage['cars'] == 'cars_consumption') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/carConsumptions/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-wrench"></span></span>
                                                        <span class="nav-link-text">ملفات إستهلاك السيارات</span>
                                                    </div>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </li>
                                    @endif
                             </ul>
                        @endif
                    @endif
                    </ul> 
                    <div class="navbar-vertical-divider">
                        <hr class="navbar-vertical-hr my-2" />
                    </div>
                <ul class="navbar-nav flex-column" id="navbarVerticalNav1">
                    @if(
                        \Auth::user()->can('workshops_module_motors_manage') ||
                        \Auth::user()->can('workshops_module_machines_manage')
                    )
                        
                            <ul class="navbar-nav flex-column" id="navbarVerticalNav1">
                                @if(\Auth::user()->can('workshops_module_motors_manage'))
                                    <li class="nav-item">
                                        <a class="nav-link dropdown-indicator" href="#motors" data-toggle="collapse" role="button" @if(isset($activePage['motors'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                <span class="nav-link-text">{{ __('إدارة الورشة') }}</span>
                                            </div>
                                        </a>
                                        <ul class="nav collapse @if(isset($activePage['motors'])) show @endif" id="motors" data-parent="#navbarVerticalCollapse">
                                            <li class="nav-item @if(isset($activePage['motors'])  && $activePage['motors'] == 'motors') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/motors/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                                        <span class="nav-link-text">ملفات مولدات الورشة</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['motors']) && $activePage['motors'] == 'machines') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/machines/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-industry"></span></span>
                                                        <span class="nav-link-text">ملفات مكينات الورشة</span>
                                                    </div>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </li>
                                    @endif
                             </ul>
                    @endif
                    </ul> 
                    <div class="navbar-vertical-divider">
                        <hr class="navbar-vertical-hr my-2" />
                    </div>
                <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                    @if(
                        \Auth::user()->can('customers_module_customers_manage') ||
                        \Auth::user()->can('employees_module_employees_manage')
                    )
                        @if(\Auth::user()->can('customers_module_customers_manage'))
                            <li class="nav-item @if(isset($activePage['customers'])) active @endif">
                                <a class="nav-link" href="{{ url('/') }}/customers/manage">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                        <span class="nav-link-text">ملفات العملاء</span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @if(
                        \Auth::user()->can('employees_module_employees_manage') ||
                        \Auth::user()->can('users_module_permissions_manage') ||
                        \Auth::user()->can('users_module_roles_manage')
                        )
                        
                           
                            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                                @if(\Auth::user()->can('users_module_users_manage'))
                                    <li class="nav-item">
                                        <a class="nav-link dropdown-indicator" href="#employees" data-toggle="collapse" role="button" @if(isset($activePage['employees'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                                            <div class="d-flex align-items-center">
                                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                                <span class="nav-link-text">{{ __('إدارة الموظفين') }}</span>
                                            </div>
                                        </a>
                                        <ul class="nav collapse @if(isset($activePage['employees'])) show @endif" id="employees" data-parent="#navbarVerticalCollapse">
                                            <li class="nav-item @if(isset($activePage['employees']) &&  $activePage['employees'] == 'employees') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/employees/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                                        <span class="nav-link-text">ملفات الموظفين</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['employees'])  && $activePage['employees'] == 'teams') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/teams/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                                        <span class="nav-link-text">ملفات الفرق</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['employees'])  && $activePage['employees'] == 'jawwal_bill') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/jawwalBills/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                                        <span class="nav-link-text">فواتير جوال</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item @if(isset($activePage['employees']) && $activePage['employees'] == 'employees_advances') active @endif">
                                                <a class="nav-link" href="{{ url('/') }}/EmployeesAdvances/manage">
                                                    <div class="d-flex align-items-center">
                                                        <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                                        <span class="nav-link-text">سلف الموظفين</span>
                                                    </div>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </li>
                                    @endif
                             </ul>
                        @endif
                    @endif
                    </ul> 
                    <div class="navbar-vertical-divider">
                        <hr class="navbar-vertical-hr my-2" />
                    </div>
                 <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                    @if(\Auth::user()->can('users_module_users_manage'))
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator" href="#users" data-toggle="collapse" role="button" @if(isset($activePage['users'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                    <span class="nav-link-text">{{ __('إدارة المستخدمين') }}</span>
                                </div>
                            </a>
                            <ul class="nav collapse @if(isset($activePage['users'])) show @endif" id="users" data-parent="#navbarVerticalCollapse">
                                <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'roles') active @endif">
                                    <a class="nav-link" href="{{ url('/') }}/users/roles/manage">{{ __('الأدوار') }}</a>
                                </li>
                                
                                <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'permissions') active @endif">
                                    <a class="nav-link" href="{{ url('/') }}/users/permissions/manage">{{ __('الصلاحيات') }}</a>
                                </li>
                                
                                <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'users') active @endif">
                                    <a class="nav-link" href="{{ url('/') }}/users/manage">{{ __('المستخدمين') }}</a>
                                </li>
                                
                            </ul>
                        </li>
                        @endif

                 </ul>
                 <div class="navbar-vertical-divider">
                    <hr class="navbar-vertical-hr my-2" />
                </div>
                <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                    @if(
                        \Auth::user()->can('core_module_countries_manage') ||
                        \Auth::user()->can('customers_module_categories_of_contracts_manage') ||
                        \Auth::user()->can('core_module_banks_manage')
                    )
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator" href="#core" data-toggle="collapse" role="button" @if(isset($activePage['core'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-cog"></span></span>
                                <span class="nav-link-text">إدارة ثوابت النظام</span>
                            </div>
                        </a>
                        <ul class="nav collapse @if(isset($activePage['core'])) show @endif" id="core" data-parent="#navbarVerticalCollapse">
                            @if(\Auth::user()->can('employees_module_departments_manage'))
                            <li class="nav-item @if(isset($activePage['core']) && $activePage['core'] == 'departments') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/departments/manage">إدارة الأقسام</a>
                            </li>
                            @endif
                            @if(\Auth::user()->can('core_module_countries_manage'))
                            <li class="nav-item @if(isset($activePage['core']) && $activePage['core'] == 'countries') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/countries/manage">إدارة بيانات الدول</a>
                            </li>
                            @endif
                            @if(\Auth::user()->can('core_module_banks_manage'))
                            <li class="nav-item @if(isset($activePage['core']) && $activePage['core'] == 'banks') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/banks/manage">إدارة بيانات البنوك</a>
                            </li>
                            @endif
    
                            @if(\Auth::user()->can('customers_module_categories_of_contracts_manage'))
                            <li class="nav-item @if(isset($activePage['core']) && $activePage['core'] == 'categories_of_contracts') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/categories_of_contracts/manage">إدارة أنواع العقود</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    
                    @endif 
                 </ul>
            </ul> 
        </div>
    </div>
</nav>
