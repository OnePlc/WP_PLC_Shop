//jQuery(document).ready(function($) {
console.log('init basket script');
function plcLoadBasket() {
    jQuery.post(basketAjax.ajaxurl, {
        action: 'plc_showbasket'
    }, function (retHTML) {
        jQuery('.plc-shop-basket').html(retHTML);
    });
}
//});