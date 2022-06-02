@extends('layouts.app')

@section('css')
    <style>
        #dashboard-logo {
            opacity: 0.3;
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="card-deck">
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>المستخدمين</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">{{ $users_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="users/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>الموظفين</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">{{ $employees_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="employees/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>العملاء</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">{{ $customers_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="customers/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>سلة المشتريات</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif text-info">{{ $carts_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="products/carts/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
        <div class="text-center">
            <figure class="figure" style="max-width: 30rem; width: 23rem;">
                <img width="130" class="img-fluid rounded" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}" alt="{{ config('app.name', 'Qasetli Portal') }}">
            </figure>
        </div>
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>المفضلة</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif text-info">{{ $wishlists_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="products/wishlists/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>التصنيفات</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif text-warning">{{ $categories_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="products/categories/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>المواصفات</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif text-warning">{{ $product_attributes_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="products/attributes/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
        <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
            <div class="card-body position-relative">
                <h6>المنتجات</h6>
                <div class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif text-warning">{{ $products_count }}</div>
                <a class="font-weight-semi-bold fs--1 text-nowrap" href="products/manage">مشاهدة الكل<span class="fas fa-angle-left ml-1" data-fa-transform="down-1"></span></a>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
@endsection
