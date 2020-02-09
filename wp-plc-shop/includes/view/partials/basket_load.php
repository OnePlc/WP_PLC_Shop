<div class="plc-shop-basket">
    <img src="<?=plugins_url('assets/img/ajax-loader.gif', WPPLC_CONNECT_PLUGIN_MAIN_FILE)?>" />
    <br/>
    <?=__('Basket is loading, please wait..','wp-plc-shop')?>
</div>
<script>
jQuery(document).ready(function($) {
    plcLoadBasket();
});
</script>