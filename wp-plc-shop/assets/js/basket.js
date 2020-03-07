function plcLoadBasket() {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_showbasket'
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}

function addItemToBasket(fAmount,iItemID,sItemType) {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_addtobasket',
        shop_item_amount: fAmount,
        shop_item_id: iItemID,
        shop_item_type: sItemType
    }, function (retHTML) {
        WPPLC_Shop_printMessage('Item added to basket','success',5,true)
    });
}

jQuery(function() {
    jQuery('.plc-shop-additemwithamount-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var sItemType = jQuery(this).parent('div').parent('div').parent('div').find('.plc-slider-slide-price').attr('plc-item-type');
        var iAmount = jQuery(this).parent('div').parent('div').parent('div').find('.plc-slider-slide-amount').val();

        addItemToBasket(iAmount,iItemID,sItemType)
    });

    jQuery('.plc-shop-additem-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var sItemType = jQuery(this).attr('plc-item-type');

        jQuery.post(basketAjax.ajaxurl, {
            action: 'plc_popupbasket',
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
                confirmButtonText: '<i class="fas fa-cart-arrow-down plc-shop-popup-buy-icon"></i> Kaufen',
                showLoaderOnConfirm: true,
                html:retHTML
            }).then(function(result) {
                var iAmount = jQuery('#plc-shop-popup-basket-amount').val();
                var iArticleID = jQuery('#plc-shop-popup-basket-article').val();
                var sArticleType = jQuery('#plc-shop-popup-basket-article').attr('plc-article-type');

                if (result.value) {
                    addItemToBasket(iAmount,iArticleID,sArticleType)
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