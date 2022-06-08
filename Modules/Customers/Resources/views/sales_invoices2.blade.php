@extends('layouts.app')

@section('css')
    <link href="{{asset('/resources/fine-uploader/5.16.2/fine-uploader-gallery.css')}}" rel="stylesheet" type="text/css" />
    <style>
        #product-images [data-action="productImageDestroy"],
        #images [data-action="productImageDestroy"]{
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            width: 50px;
            height: 50px;
            font-size: 23px;
            color: #f44336;
            cursor: pointer;
        }

        #product-images [data-action="productImageDestroy"] svg,
        #images [data-action="productImageDestroy"] svg{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endsection

@section('content')
    <form id="product-edit" data-id="{{ $product_id }}">
        <div class="card mb-3 mt-2">
            <div class="card-header">
                <h5 class="mb-0">إضافة بيانات فاتورة</h5>
            </div>
        </div>
        <div class="card mb-3">
            <h5 class="card-header">البيانات الأساسية</h5>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>اسم المنتج (*)</label>
                                    <input class="form-control" name="name" type="text" placeholder="اسم المنتج">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>التصنيف (*)</label>
                                    <select class="form-control" name="category_id" data-placeholder="البحث في التصنيفات" data-options_source="children_categories"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>الكمية المتوفرة (*)</label>
                                    <input class="form-control" name="quantity" type="number" placeholder="الكمية المتوفرة">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>النوع (*)</label>
                                    <select class="form-control" name="type" data-options_source="product_types"></select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>تاريخ الانتهاء</label>
                                    <input class="form-control datetimepicker" name="end_at" type="text" placeholder="تاريخ الانتهاء" autocomplete="off" data-options='{"dateFormat":"Y-m-d", "allowInput": true}'>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <h5 class="card-header">بيانات السعر</h5>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>السعر (*)</label>
                            <input class="form-control" name="price" type="number" placeholder="السعر">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>السعر بعد الخصم</label>
                            <input class="form-control" name="price_after_discount" type="number" placeholder="السعر بعد الخصم">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>العملة (*)</label>
                            <select class="form-control" name="currency_id" data-placeholder="البحث في العملات" data-options_source="currencies"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">بيانات العنوان</h5>
            <div class="card-body bg-light">
                <div class="row">
                    <div class="col-4">
                        <label>المحافظة (*)</label>
                        <select class="form-control" name="province_id" data-placeholder="البحث في المحافظات" data-options_source="provinces"></select>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input class="form-control" name="address" type="text" placeholder="العنوان">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">بيانات المواصفات</h5>
            <div class="card-body bg-light">
                <div class="card-body pt-0 pl-0 pr-0" id="product-attributes">
                    <div class="attributes"></div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">صور المنتج</h5>
            <div class="card-body bg-light">
                <div class="text-left" id="product-images" data-section-name="images">
                    <span class="block-card"></span>
                    <div class="card-body pt-0 pl-0 pr-0">
                        <div id="uploader"></div>
                        <div class="row text-center text-lg-center mt-4" images>
                            <div class="col-lg-3 col-md-4 col-6" image>
                                <a class="d-block mb-4 h-100" style="position: relative;">
                                    <span data-action="productImageDestroy" title="حذف الصورة"><i class="fa fa-trash"></i></span>
                                    <img class="img-fluid img-thumbnail" src="" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-primary mb-3 w-50" type="submit">تحديث البيانات</button>
        </div>
    </form>
@endsection

@section('javascript')
    <script src="{{asset('/resources/fine-uploader/5.16.2/fine-uploader.js')}}" type="text/javascript"></script>

    <script type="text/template" id="qq-template">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="قم بوضع الملفات هنا">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>رفع ملف</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>جاري معالجة الملفات...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <div class="qq-thumbnail-wrapper">
                        <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                    </div>
                    <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                    <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                        <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                        إعادة المحاولة
                    </button>

                    <div class="qq-file-info">
                        <div class="qq-file-name">
                            <span class="qq-upload-file-selector qq-upload-file"></span>
                            <span class="qq-edit-filename-icon-selector qq-btn qq-edit-filename-icon" aria-label="Edit filename"></span>
                        </div>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                            <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                            <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                            <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                        </button>
                    </div>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">إغلاق</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">لا</button>
                    <button type="button" class="qq-ok-button-selector">نعم</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">إلغاء</button>
                    <button type="button" class="qq-ok-button-selector">موافق</button>
                </div>
            </dialog>
        </div>
    </script>
    <script src="{{ asset('/Modules/Products/Resources/assets/js/edit-product.js?vid=20220106') }}"></script>
@endsection
