function plcLoadBasket() {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_showbasket'
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}

function plcBasketRemovePos(iPosID) {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_basketupdatepos',
        position_mode: 'remove',
        position_id: iPosID
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}

function plcBasketUpdatePos(iPosID,iNewAmount) {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_basketupdatepos',
        position_mode: 'update',
        position_id: iPosID,
        position_amount: iNewAmount
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}

function addItemToBasket(fAmount,iItemID,sItemType,sItemComment,iCustomPrice,iRefID,sRefType,sItemLabel) {
    if (sItemComment === undefined) sItemComment = "";
    if (iCustomPrice === undefined) iCustomPrice = 0;
    if (iRefID === undefined) iRefID = 0;
    if (sRefType === undefined) sRefType = 'none';
    if (sItemLabel === undefined) sItemLabel = '-';
    var sItemDescNameSingle = 'Ihr Gutschein';
    var sItemDescNameMulti = 'Ihre '+fAmount+' Gutscheine';
    sItemLabel = sItemLabel.replace(/(\r\n|\n|\r)/gm, "");
    if(sItemComment.length > 1) {
        sItemLabel += ' mit persönlicher Widmung';
    }
    if(sRefType == 'event') {
        sItemDescNameSingle = 'Ihr Event-Ticket';
        sItemDescNameMulti = 'Ihre '+fAmount+' Event-Tickets';
    }
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_addtobasket',
        shop_item_amount: fAmount,
        shop_item_id: iItemID,
        shop_item_type: sItemType,
        shop_item_comment:sItemComment,
        shop_item_customprice:iCustomPrice,
        shop_item_ref_idfs:iRefID,
        shop_item_ref_type:sRefType
    }, function (retHTML) {
        var iCurCount = parseInt(jQuery('.plc-shop-badge-counter').html());
        iCurCount = iCurCount+parseInt(fAmount);
        jQuery('.plc-shop-badge-counter').html(iCurCount);
        if(fAmount == 1) {
            WPPLC_Shop_printMessage(sItemDescNameSingle+' "'+sItemLabel+'" wurde in den Warenkorb gelegt','success',5,true)
        } else {
            WPPLC_Shop_printMessage(sItemDescNameMulti+' "'+sItemLabel+'" wurden in den Warenkorb gelegt','success',5,true)
        }
    });
}

jQuery(function() {
    jQuery('.plc-shop-additemsinglebox-tobasket').on('click',function() {
        var iItemID = jQuery(this).parent('div').parent('div').parent('div').find('.plc-art-price').val();
        var sItemType = jQuery(this).parent('div').parent('div').parent('div').find('.plc-art-price').attr('plc-item-type');
        var iAmount = jQuery(this).parent('div').parent('div').parent('div').find('.plc-art-amount').val();
        var sItemComment = jQuery(this).parent('div').parent('div').parent('div').find('.plc-shop-featured-product-description');
        var sItemLabel = jQuery(this).parent('div').parent('div').parent('div').find('.plc-art-price option:selected').html();
        if(sItemComment) {
            sItemComment = sItemComment.val();
        } else {
            sItemComment = '';
        }
        var iCustomPrice = jQuery(this).parent('div').parent('div').parent('div').find('.plc-shop-featured-product-custom-price');
        if(iCustomPrice) {
            iCustomPrice = iCustomPrice.val();
            sItemLabel = 'Wertgutschein Wunschbetrag ' +parseFloat(iCustomPrice).toFixed(2)+' '+basketAjax.shopCurrency;
        } else {
            iCustomPrice = 0;
        }
        addItemToBasket(iAmount,iItemID,sItemType,sItemComment,iCustomPrice,0,'none',sItemLabel);
    });

    jQuery('.plc-shop-additemwithamount-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var sItemType = jQuery(this).parent('div').parent('div').parent('div').find('.plc-slider-slide-price').attr('plc-item-type');
        var iAmount = jQuery(this).parent('div').parent('div').parent('div').find('.plc-slider-slide-amount').val();

        addItemToBasket(iAmount,iItemID,sItemType)
    });

    jQuery('.plc-shop-additem-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var iVariantID = jQuery(this).attr('plc-item-variant');
        if(iVariantID == undefined) {
            iVariantID = 0;
        }
        var sItemType = jQuery(this).attr('plc-item-type');
        var sButtonLabel = 'Kaufen';
        if(sItemType == 'event') {
            sButtonLabel = 'Buchen';
        }

        jQuery.post(basketAjax.ajaxurl, {
            action: 'plc_popupbasket',
            shop_item_id: iItemID,
            shop_item_variant_id: iVariantID,
            shop_item_type: sItemType
        }, function (retHTML) {
            Swal.fire({
                background:'#fff',
                padding:'8px 8px 4px 8px',
                border:'none',
                borderRadius:'0',
                width:'600px',
                showCancelButton: true,
                cancelButtonText: 'Abbrechen',
                confirmButtonText: '<i class="fas fa-cart-arrow-down plc-shop-popup-buy-icon"></i> '+sButtonLabel,
                showLoaderOnConfirm: true,
                html:retHTML
            }).then(function(result) {
                var iAmount = jQuery('#plc-shop-popup-basket-amount').val();
                var iArticleID = jQuery('#plc-shop-popup-basket-article').val();
                var sArticleType = jQuery('#plc-shop-popup-basket-article').attr('plc-article-type');
                var iRefID = 0;
                var sRefType = 'none';
                var sItemLabel = jQuery('#plc-shop-popup-basket-article option:selected').html();
                if(jQuery('#plc-shop-popup-basket-ref-idfs')) {
                    iRefID = jQuery('#plc-shop-popup-basket-ref-idfs').val();
                    sRefType = jQuery('#plc-shop-popup-basket-ref-type').val();
                }
                if(jQuery('#plc-shop-popup-basket-ref-label')) {
                    var refLabel = jQuery('#plc-shop-popup-basket-ref-label').val();
                    if(refLabel != undefined && refLabel != '') {
                        sItemLabel = refLabel + ': ' +sItemLabel;
                    }
                }
                if (result.value) {
                    addItemToBasket(iAmount,iArticleID,sArticleType,'',0, iRefID, sRefType, sItemLabel);
                }
            });
        });

        return false;
    });

    jQuery('.plc-shop-giftitem-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var sItemType = jQuery(this).attr('plc-item-type');

        jQuery.post(basketAjax.ajaxurl, {
            action: 'plc_popupgift',
            shop_item_id: iItemID,
            shop_item_type: sItemType
        }, function (retHTML) {
            Swal.fire({
                background:'#fff',
                padding:'8px 8px 4px 8px',
                border:'none',
                borderRadius:'0',
                width:'600px',
                showCancelButton: true,
                cancelButtonText: 'Abbrechen',
                confirmButtonText: '<i class="fas fa-cart-arrow-down plc-shop-popup-buy-icon"></i> Verschenken',
                showLoaderOnConfirm: true,
                html:retHTML
            }).then(function(result) {
                var iAmount = 1;
                var iArticleID = jQuery('#plc-shop-popup-basket-article').val();
                var sArticleType = jQuery('#plc-shop-popup-basket-article').attr('plc-article-type');
                var sComment = jQuery('.plc-shop-popup-textarea').val();
                var iRefID = 0;
                var sRefType = 'none';
                var sItemLabel = jQuery('#plc-shop-popup-basket-article option:selected').html();
                if(jQuery('#plc-shop-popup-basket-ref-idfs')) {
                    iRefID = jQuery('#plc-shop-popup-basket-ref-idfs').val();
                    sRefType = jQuery('#plc-shop-popup-basket-ref-type').val();
                    if(jQuery('#plc-shop-popup-basket-ref-label')) {
                        var refLabel = jQuery('#plc-shop-popup-basket-ref-label').val();
                        if(refLabel != undefined && refLabel != '') {
                            sItemLabel = refLabel + ': ' +sItemLabel;
                        }
                    }
                }
                if (result.value) {
                    addItemToBasket(iAmount,iArticleID,sArticleType,sComment,0,iRefID,sRefType,sItemLabel);
                }
            });
        });

        return false;
    });
});

/**
 * Print Message in Popup, tailored for Shop with Buttons
 *
 * @param message
 * @param type
 * @param closeDelay
 * @constructor
 */
function WPPLC_Shop_printMessage(message,type,closeDelay,bShowButtons) {
    if(bShowButtons === undefined) bShowButtons = true;

    if(bShowButtons) {
        Swal.fire({
            icon: type,
            html: message,
            width:'600px',
            showConfirmButton: false,
            footer: '<div style="width:50%; float:left;"><a class="plc-shop-popup-button" href="/'+basketAjax.shopBasketSlug+'">Zum Warenkorb</a></div><div style="width:50%; float:left; padding-left:8px;"><a class="plc-shop-popup-button" href="javascript:Swal.close();">Weiter einkaufen</a></div>'
        })
    } else {
        Swal.fire({
            icon: type,
            html: message,
            showConfirmButton: true,
        })
    }
}