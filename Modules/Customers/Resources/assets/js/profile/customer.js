var customer_id = $('#customer-main-tabs').attr('data-customer-id');

$(function() {
    
    GLOBALS.lists.provinces($('[data-options_source="provinces"]'));

    $('#customer-main-tabs > .nav-bar [data-tab-hash]').click(function () {
        location.hash = $(this).attr('data-tab-hash');

        $('#customer-main-tabs [data-tab]').removeClass('active');
        $('#customer-main-tabs [data-tab="' + location.hash + '"]').addClass('active');
    });

    if (location.hash.length > 0) {
        $('#customer-main-tabs [data-tab-hash="' + location.hash.substr(1, location.hash.length) + '"]').trigger('click');
    }


 
    // ------------------- Contracts -----------------

    $("#datatable-contracts").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/contracts'
        },
      
        formatters: {
            contract_image: function(row, column){
         return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-${row.id}">
                    صورة العقد
                </button>
                <div class="modal fade" id="imge-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">صورة العقد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.contract_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
                            </iframe>
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            },
            contentForProduct:function(row,column){
                return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                    تفاصيل إضافية للمولد
                </button> 
                <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"> تفاصيل إضافية للمولد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            ${row.product_for_user.other_details}
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            }, 
            contentForContract : function(row,column){
                return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-${row.id}">
                    تفاصيل أخرى للعقد 
                </button>
                <div class="modal fade" id="contract-addition-details-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">تفاصيل أخرى للعقد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            ${row.note.content}
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            }, 
        }
    });
    
    // ------------------- Products -----------------
    intervalId = window.setInterval(function(){
        $('.modal-backdrop').removeAttr('class');
      }, 1000);

    $("#datatable-products").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/products'
        },
        formatters: {
            contract_image: function(row, column){
         return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#imge-contract-${row.id}">
                    صورة العقد
                </button>
                <div class="modal fade" id="imge-contract-${row.id}" tabindex="-1" role="dialog" aria-labelledby="imge-contract-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">صورة العقد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.contract_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
                            </iframe>
                          
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            },
            contentForProduct:function(row,column){
                return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#contract-addition-details-4-${row.id}">
                    تفاصيل إضافية للمولد
                </button> 
                <div class="modal fade" id="contract-addition-details-4-${row.id}" tabindex="-1" role="dialog" aria-labelledby="contract-addition-details-4-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"> تفاصيل إضافية للمولد</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            ${row.product_for_user.other_details}
                        
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            }, 
            
        }
    });


    // ------------------- ReceiptStatements -----------------
    
    $("#datatable-receipt-statements").briskDataTable({
        resource: {
            api: $("meta[name='BASE_URL']").attr("content"),
            entity: 'customers/' + customer_id + '/receipt_statements'
        },
        formatters: {
            opposite: function(row, column){
         return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#receipt_statements-opposite-${row.id}">
                    المقابل
                </button>
                <div class="modal fade" id="receipt_statements-opposite-${row.id}" tabindex="-1" role="dialog" aria-labelledby="receipt_statements-opposite-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">المقابل</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            ${row.opposite}
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            },
            other_terms:function(row,column){
                return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#receipt_statements-other-terms${row.id}">
                   شروط متغيرة
                </button> 
                <div class="modal fade" id="receipt_statements-other-terms${row.id}" tabindex="-1" role="dialog" aria-labelledby="receipt_statements-other-terms${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">شروط متغيرة</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            ${row.other_terms}
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            }, 
            image : function(row,column){
                return `<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#image-of-receipt_statement-${row.id}">
                    صورة إيصال القبض 
                </button>
                <div class="modal fade" id="image-of-receipt_statement-${row.id}" tabindex="-1" role="dialog" aria-labelledby="image-of-receipt_statement-${row.id}Title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"> صورة إيصال القبض </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" height="600px">
                            <iframe src="https://docs.google.com/viewerng/viewer?url=${row.receipt_statement_image_url}&embedded=true" frameborder="0"  style="height:400px;"  width="100%">
                            </iframe>
                         </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                        </div>
                    </div>
                </div>`;
            }, 
        }
    });

});
