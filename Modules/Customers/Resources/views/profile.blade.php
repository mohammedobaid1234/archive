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
            display: none;
        }

        #product-images [data-action="productImageDestroy"] svg,
        #images [data-action="productImageDestroy"] svg{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #product-images [images] [image]:hover [data-action="productImageDestroy"],
        #images [images] [image]:hover [data-action="productImageDestroy"]{
            display: block;
        }

        #customer-main-tabs .tab-indicator{
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="row no-gutters">
        <div class="col-md pr-lg-2">
            <div class="card mb-3">
                <div class="card-body bg-light p-0">
                    <div class="fancy-tab" id="customer-main-tabs" data-customer-id="{{ $customer->id }}">
                        <div class="nav-bar nav-bar-left p-1">
                            <div class="nav-bar-item px-3 px-sm-4 active" data-tab-hash="information">الصفحة الشخصية</div>
                            <div class="nav-bar-item px-3 px-sm-4" data-tab-hash="contracts">العقود</div>
                            <div class="nav-bar-item px-3 px-sm-4" data-tab-hash="products">المولدات</div>

                        </div>
                        <div class="tab-contents mt-3">
                            <div class="tab-content active" data-tab="#information" id="customer-settings">
                                <div class="row no-gutters">
                                    <div class="col-lg-12">

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card mb-3">
                                                        <div class="card-header">
                                                            <h5 class="mb-0">بيانات الحساب</h5>
                                                        </div>
                                                        <div class="card-body bg-light">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">رقم الملف</div>
                                                                    <div class="col-md-6">{{ $customer->id }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">الاسم</div>
                                                                    <div class="col-md-6">{{ $customer->full_name }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">نوع الحساب</div>
                                                                    <div class="col-md-6">{{ $customer->type }}</div>
                                                                </div>
                                                            </div>
                                                            @if($customer->type == "شركة")
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-6 pl-0">اسم الشركة</div>
                                                                        <div class="col-md-6">{{ $customer->company->name }}</div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">رقم الجوال</div>
                                                                    <div class="col-md-6">{{ $customer->mobile_no }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">المحافظة</div>
                                                                    <div class="col-md-6">
                                                                        @if( $customer->province )
                                                                            {{ $customer->province->name }}
                                                                            @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 pl-0">العنوان</div>
                                                                    <div class="col-md-6">{{ $customer->address }}</div>
                                                                </div>
                                                            </div>
                                                            
                                                            <a class="btn btn-falcon-default d-block mt-3" data-id="{{ $customer->id }}" data-action="customer-update">تحديث البيانات</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" data-tab="#contracts">
                                <div id="datatable-contracts"></div>
                            </div>
                            <div class="tab-content" data-tab="#products">
                                <div id="datatable-products"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div id="customer-data-modal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" data-province-id="{{ $customer->province_id }}" data-province-name="{{ isset($customer->province) ? $customer->province->name : NULL }}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h4 class="modal-title">تحديث البيانات</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-alerts"></div>
                <form role="form" autocomplete="off">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                    <div class="form-group no-margin-hr">
                                    <label class="control-label">رقم الجوال (*)</label>
                                    <input type="text" name="mobile_no" placeholder="" class="form-control" autocomplete="off" maxlength="10" required value="{{ $customer->mobile_no }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                    <div class="form-group no-margin-hr">
                                    <label class="control-label">نوع الحساب (*)</label>
                                    <select name="type" class="form-control" autocomplete="off" required>
                                        <option value="شخصي" {{$customer->type == 'شخصي'?'selected':''}} >شخصي</option>
                                        <option value="تاجر" {{$customer->type == 'تاجر'?'selected':''}} >تاجر</option>
                                        <option value="شركة" {{$customer->type == 'شركة'?'selected':''}} >شركة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                    <div class="form-group no-margin-hr">
                                    <label class="control-label">اسم الشركة</label>
                                    <input type="text" name="company_name" class="form-control" autocomplete="off" value="{{ $customer->company ? $customer->company->name : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                    <div class="form-group no-margin-hr">
                                    <label class="control-label">المحافظة</label>
                                    <select class="form-control" name="province_id" data-placeholder="البحث في المحافظات" data-options_source="provinces"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                    <div class="form-group no-margin-hr">
                                    <label class="control-label">العنوان</label>
                                    <input type="text" name="address" class="form-control" autocomplete="off" value="{{ $customer->address }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer sm-text-center">
                        <button type="submit" class="btn btn-primary btn-block fs-15" data-original-html="حفظ البيانات">حفظ البيانات</button>
                        <button type="reset" class="d-none">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

    <script src="{{ asset('/Modules/Customers/Resources/assets/js/profile/customer.js?vid=202110104') }}"></script>
@endsection
