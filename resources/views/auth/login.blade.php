<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="BASE_URL" content="{{ url('/') }}">

        <!-- ===============================================-->
        <!--    Document Title-->
        <!-- ===============================================-->
        <title>{{ config('app.name', 'Qasetli') }}</title>

        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/favicon-16x16.png') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/favicon.ico') }}">
        <link rel="manifest" href="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/manifest.json') }}">
        <meta name="msapplication-TileImage" content="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/mstile-150x150.png') }}">
        <meta name="theme-color" content="#ffffff">

        <!-- ===============================================-->
        <!--    Stylesheets-->
        <!-- ===============================================-->
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/config.navbar-vertical.js') }}"></script>
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/lib/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">

        <link href="{{ asset('/public/themes/Falcon/v2.8.0/assets/css/theme-rtl.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/custom-common.css') }}" rel="stylesheet">
        <link href="{{ asset('/public/themes/Falcon/v2.8.0/custom-rtl.css') }}" rel="stylesheet">
    </head>
    <body>
        <!-- ===============================================-->
        <!--    Main Content-->
        <!-- ===============================================-->
        <main class="main" id="top">
            <div class="container" data-layout="container">
            <div class="row flex-center min-vh-100 py-6">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <a class="d-flex flex-center mb-4" href="{{ url('/') }}">
                        <img class="mr-2" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}" alt="Qasetli" width="200" />
                    </a>
                    <span class="d-flex flex-center mb-4 text-stc font-weight-extra-bold fs-4 d-inline-block">نظام إدارة الشركات</span>
                    <div class="card">
                        <div class="card-body p-4 p-sm-4">
                            <div class="row text-left justify-content-between align-items-center mb-2">
                                <div class="col-auto">
                                    <h5>{{ __('تسجيل الدخول') }}</h5>
                                </div>
                            </div>
                            <form id="login">
                                <div class="form-group">
                                    <input name="employment_id" class="form-control" type="text" placeholder="الرقم الوظيفي..." autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input name="password" class="form-control" type="password" placeholder="كلمة المرور..." />
                                </div>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="remember" checked="checked" name="remember" />
                                            <label class="custom-control-label" for="remember">{{ __('تذكرني') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <button class="btn btn-primary btn-block mt-3" type="submit" name="submit">{{ __('تسجيل الدخول') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </main>
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
        <script src="{{ asset('/public/themes/Falcon/v2.8.0/assets/js/theme.js') }}"></script>

		<script src="{{ asset('/public/themes/Falcon/v2.8.0/libs/sweetalert/2.1.0/sweetalert.min.js') }}"></script>

		<script src="{{ asset('/resources/js/http.js') }}"></script>
		<script src="{{ asset('/resources/js/login.js') }}"></script>
    </body>
</html>
