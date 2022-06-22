<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="BASE_URL" content="{{ url('/') }}">
		<meta name="USER_ID" content="{{ \Auth::user()->id }}">

        <!-- ===============================================-->
        <!--    Document Title-->
        <!-- ===============================================-->
        <title>{{ config('app.name', 'Archive') }}</title>

        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}">
        <link rel="manifest" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/manifest.json') }}">
        <meta name="msapplication-TileImage" content="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/mstile-150x150.png') }}">
        <meta name="theme-color" content="#ffffff">

        <!-- ===============================================-->
        <!--    Stylesheets-->
        <!-- ===============================================-->
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/config.navbar-vertical.js') }}"></script>
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/select2/select2.min.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/select2-theme/select2-bootstrap4.min.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/toastr/toastr.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/css/theme-rtl.css') }}" rel="stylesheet">

        <link href="{{ asset('/Modules/BriskCore/Resources/assets/libs/brisk-datatable/css/common.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/custom-common.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/custom-rtl.css') }}" rel="stylesheet">

        @yield('css')
    </head>
    <body>
        <!-- ===============================================-->
        <!--    Main Content-->
        <!-- ===============================================-->
        <main class="main" id="top">
            <div class="container-fluid" data-layout="container">
                @include('layouts/navigation')

                <div class="content">
                    <nav class="navbar navbar-light navbar-glass navbar-top sticky-kit navbar-expand-lg navbar-top-combo" data-move-target="#navbarVerticalNav">
                        <button class="btn navbar-toggler-humburger-icon navbar-toggler mr-1 mr-sm-3" type="button" data-toggle="collapse" data-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
                        <a class="navbar-brand mr-1 mr-sm-3" href="{{ url('/') }}">
                            <div class="d-flex align-items-center">
                                <!-- <img class="mr-2" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/illustrations/falcon.png') }}" alt="" width="40" /> -->
                                <span class="text-sans-serif">AMTC</span>
                            </div>
                        </a>
                        <ul class="navbar-nav align-items-center d-none d-lg-block"></ul>
                        <div class="collapse navbar-collapse" id="navbarStandard">
                            <ul class="navbar-nav">
                                {{-- <li class="nav-item dropdown dropdown-on-hover"> --}}
                                    <!-- <a class="nav-link dropdown-toggle" id="navbarDropdownHome" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Home</a>
                                    <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownHome">
                                        <div class="bg-white rounded-soft py-2"><a class="dropdown-item" href="../index.html">Dashboard</a><a class="dropdown-item" href="../home/dashboard-alt.html">Dashboard alt</a><a class="dropdown-item" href="../home/feed.html">Feed</a><a class="dropdown-item" href="../home/landing.html">Landing</a></div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown dropdown-on-hover">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdownPages" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                                    <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownPages">
                                        <div class="card navbar-card-pages shadow-none">
                                            <div class="card-body scrollbar perfect-scrollbar max-h-dropdown">
                                            <img class="position-absolute b-0 r-0" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/illustrations/authentication-corner.png') }}" width="130" alt="" />
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    <div class="nav flex-column"><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/activity.html">Activity</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/associations.html">Associations</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/billing.html">Billing</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/customer-details.html">Customer details</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/event-detail.html">Event detail</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/event-create.html">Event create</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/events.html">Events</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/faq.html">Faq</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/invoice.html">Invoice</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/invite-people.html">Invite people</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/notifications.html">Notifications</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/people.html">People</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/pricing.html">Pricing</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/pricing-alt.html">Pricing alt</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="nav flex-column">
                                                        <a class="nav-link py-1 text-700 font-weight-medium" href="../pages/profile.html">Profile</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/settings.html">Settings</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/starter.html">Starter</a><a class="nav-link py-1 text-700 font-weight-medium" href="../calendar.html">Calendar</a><a class="nav-link py-1 text-700 font-weight-medium" href="../chat.html">Chat</a><a class="nav-link py-1 text-700 font-weight-medium" href="../kanban.html">Kanban</a><a class="nav-link py-1 text-700 font-weight-medium" href="../widgets.html">Widgets</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/errors/404.html">404</a><a class="nav-link py-1 text-700 font-weight-medium" href="../pages/errors/500.html">500</a>
                                                        <div class="nav-link py-1 text-900 font-weight-bold mt-3">Emails</div>
                                                        <a class="nav-link py-1 text-700 font-weight-medium" href="../email/inbox.html">Inbox</a><a class="nav-link py-1 text-700 font-weight-medium" href="../email/email-detail.html">Email detail</a><a class="nav-link py-1 text-700 font-weight-medium" href="../email/compose.html">Compose</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <div class="nav flex-column">
                                                        <div class="nav-link py-1 text-900 font-weight-bold">E-commerce</div>
                                                        <a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/product-list.html">Product list</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/product-grid.html">Product grid</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/product-details.html">Product details</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/orders.html">Orders</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/order-details.html">Order details</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/customers.html">Customers</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/shopping-cart.html">Shopping cart</a><a class="nav-link py-1 text-700 font-weight-medium" href="../e-commerce/checkout.html">Checkout</a>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown dropdown-on-hover">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdownDocumentation" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Documentation</a>
                                    <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownDocumentation">
                                        <div class="bg-white rounded-soft py-2"><a class="dropdown-item" href="../documentation/getting-started.html">Getting started</a><a class="dropdown-item" href="../documentation/file-structure.html">File structure</a><a class="dropdown-item" href="../documentation/customization.html">Customization</a><a class="dropdown-item" href="../documentation/dark-mode.html">Dark mode</a><a class="dropdown-item" href="../documentation/fluid-layout.html">Fluid layout</a><a class="dropdown-item" href="../documentation/gulp.html">Gulp</a><a class="dropdown-item" href="../documentation/RTL.html">RTL</a><a class="dropdown-item" href="../documentation/plugins.html">Plugins</a>
                                        </div>
                                    </div>
                                </li> -->
                            </ul>
                        </div>
                        <ul class="navbar-nav navbar-nav-icons ml-auto flex-row align-items-center">
                            
                            <li class="nav-item">
                                <a class="nav-link px-0" href="{{ \Config::get('app.website_domain') }}" target="_blank" title="الانتقال إلى الموقع الإلكتروني">
                                    <span class="fas fa-home fs-4" data-fa-transform="shrink-7"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-0" data-action="hard-reload" title="تحديث النظام">
                                    <span class="fas fa-sync fs-4" data-fa-transform="shrink-7"></span>
                                </a>
                            </li>
                            {{-- <li class="nav-item dropdown dropdown-on-hover">
                                <a class="nav-link notification-indicator notification-indicator-primary px-0 icon-indicator" id="navbarDropdownNotification" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-bell fs-4" data-fa-transform="shrink-6"></span></a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-card" aria-labelledby="navbarDropdownNotification">
                                    <div class="card card-notification shadow-none" style="max-width: 20rem">
                                        <div class="card-header">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <h6 class="card-header-title mb-0">الإشعارات</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="list-group list-group-flush font-weight-normal fs--1">
                                            <div id="notification"></div>
                                        </div>
                                        <div class="card-footer text-center border-top show-all-notifications"><a class="card-link d-block" href="{{ url('/') }}/employees/notifications/manage">عرض الكل</a></div>
                                        <div class="card-footer text-center border-top not-have-notifications d-none">لا يوجد أي اشعارات جديدة</div>
                                    </div>
                                </div>
                            </li> --}}
                            <li class="nav-item dropdown dropdown-on-hover">
                                <a class="nav-link pr-0" id="navbarDropdownUser" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar avatar-xl">
                                        <img class="rounded-circle" src="{{ \Auth::user()->personal_image_url }}" alt="" />
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="navbarDropdownUser">
                                    <div class="bg-white rounded-soft py-2">
                                        <a class="dropdown-item font-weight-bold text-warning" href="#"><span>{{ \Auth::user()->name }}</span></a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" data-action="change-password-create">{{ __('إعادة تعيين كلمة المرور') }}</a>
                                        <a class="dropdown-item" data-action="logout">{{ __('تسجيل الخروج') }}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </nav>

                    @if(isset($breadcrumb) && sizeof($breadcrumb))
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">الرئيسية</a></li>
                            @foreach($breadcrumb as $tab_key => $tab)
                                <li
                                    class="breadcrumb-item active"
                                    @if(sizeof($breadcrumb) == $tab_key + 1) aria-current="page" @endif
                                >
                                    <a @if(isset($tab['url']) && trim($tab['url']) !== "") href="{{URL::to('/')}}/{{ $tab['url'] }}" @endif>
                                    {{ $tab['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                    @endif

                    @yield('content')

                    <footer>
                        <div class="row no-gutters justify-content-between fs--1 mt-4 mb-3">
                            <div class="col-12 col-sm-auto text-center">
                                <p class="mb-0 text-600">{{ config('app.name', 'tli Portal') }} <span class="d-none d-sm-inline-block">| </span><br class="d-sm-none" /> {{ date('Y') }} &copy;</p>
                            </div>
                            <div class="col-12 col-sm-auto text-center">
                                <p class="mb-0 text-600">v0.0.1</p>
                            </div>
                        </div>
                    </footer>
                </div>

                <div id="change-password"></div>
                <div id="person"></div>

                @yield('modals')
            </div>
        </main>

        <iframe id="iframe-print" class="d-none"></iframe>
        <!-- ===============================================-->
        <!--    End of Main Content-->
        <!-- ===============================================-->

        <!-- ===============================================-->
        <!--    JavaScripts-->
        <!-- ===============================================-->
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/@fortawesome/all.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/stickyfilljs/stickyfill.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/sticky-kit/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/is_js/is.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/lodash/lodash.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/echarts/echarts.min.js') }}"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.6.15/browser-polyfill.min.js"></script> -->
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/babel-core/5.6.15/browser-polyfill.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/progressbar.js/progressbar.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/theme.js?vid=20211020') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/select2/select2.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/flatpickr/flatpickr.min.js') }}"></script>

        <script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/flatpickr/ar.js') }}"></script>
		<script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/sweetalert/2.1.0/sweetalert.min.js') }}"></script>
		<script src="{{ asset('/Modules/BriskCore/Resources/assets/libs/brisk-datatable/js/brisk-datatable.js?vid=20211204') }}"></script>
		<script src="{{ asset('/Modules/BriskCore/Resources/assets/libs/brisk-form/js/brisk-form.js') }}"></script>
		<script src="{{ asset('/Modules/BriskCore/Resources/assets/libs/brisk-selectOptions/js/brisk-selectOptions.js') }}"></script>

        @include('layouts/constants')

        <script src="{{ asset('/resources/js/globals.js?vid=20211123') }}"></script>
        <script src="{{ asset('/resources/js/http.js') }}"></script>
        <script src="{{ asset('/resources/js/lists.js?vid=20220105') }}"></script>

        @yield('javascript')
    </body>
</html>
