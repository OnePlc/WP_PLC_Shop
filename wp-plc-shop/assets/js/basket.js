function plcLoadBasket() {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_showbasket'
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}

function addItemToBasket(iItemID,sItemType) {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_addtobasket',
        shop_item_id: iItemID,
        shop_item_type: sItemType
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}