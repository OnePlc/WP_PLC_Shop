<div class="plc-shop-basket">
    <img src="<?=plugins_url('assets/img/ajax-loader.gif', WPPLC_CONNECT_PLUGIN_MAIN_FILE)?>" />
    <br/>
    <?=__('Warenkorb wird geladen. Bitte warten...','wp-plc-shop')?>
</div>
<script>
jQuery(document).ready(function($) {
    plcLoadBasket();

    $(document).on('click', '.plc-shop-basket-pos-del', function() {
        var iPosID = $(this).parent('td').parent('tr').attr('data-pos-id');
        plcBasketRemovePos(iPosID);

        return false;
    });

    $(document).on('blur', '.plc-shop-basket-pos-amount', function() {
        var iPosID = $(this).parent('td').parent('tr').attr('data-pos-id');
        var iNewAmount = $(this).val();
        if(iNewAmount > 0) {
            plcBasketUpdatePos(iPosID,iNewAmount);
        } else {
            plcBasketRemovePos(iPosID);
        }

        return false;
    });
});
</script>