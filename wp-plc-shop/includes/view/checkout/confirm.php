<form class="plc-shop-form">
    <?php if($bPaymentCancelled) { ?>
        <div class="elementor-widget-container">

            <div class="elementor-alert elementor-alert-danger" role="alert">
                <span class="elementor-alert-title elementor-inline-editing" data-elementor-setting-key="alert_title" data-elementor-inline-editing-toolbar="none">
                    Zahlung abgebrochen
                </span>
                <span class="elementor-alert-description elementor-inline-editing" data-elementor-setting-key="alert_description">
                    Bitte versuchen Sie es erneut oder ändern Sie die Zahlungsmethode.
                </span>
                <button type="button" class="elementor-alert-dismiss">
                    <span aria-hidden="true">×</span>
                    <span class="elementor-screen-only">Warnung verwerfen</span>
                </button>
            </div>
        </div>
    <?php } ?>
    <h4 class="plc-cart-summary-title">Kontakt- und Lieferadresse</h4>
    <p>
        <b>Liefermethode : </b> <?=$oInfo->deliverymethod->label?><br/>
        <b>Anschrift: </b> <br/><?=$oInfo->contact->firstname?> <?=$oInfo->contact->lastname?><br/>
        <?php if($oInfo->deliverymethod->Deliverymethod_ID == 2) { ?>
            <?=$oInfo->contact->address->street?><br/>
            <?=$oInfo->contact->address->zip?> <?=$oInfo->contact->address->city?><br/>
        <?php } ?>
        <?=$oInfo->contact->email?><br/>
        <?=$oInfo->contact->phone?><br/>
    </p>
    <hr />
    <h4 class="plc-cart-summary-title">Zahlungsart</h4>
    <ul style="list-style-type: none; margin:0;" class="plcShopPayList">
        <li>
            <i class="<?=$oInfo->paymentmethod->icon?> fa-3x" style="width:60px;"></i> <?=$oInfo->paymentmethod->label?>
        </li>
    </ul>
    <hr />

    <h4 class="plc-cart-summary-title">Ihre Bemerkungen</h4>
    <p>
        <?=$oInfo->basket->comment?>
    </p>
    <hr />

    <h4 class="plc-cart-summary-title">Ihre Bestellung</h4>
    <div class="plc-shop-basket plc-shop-basket-confirm">
        <img src="<?=WPPLC_SHOP_PUB_DIR?>/assets/img/ajax-loader.gif" />
    </div>
    <script>
        jQuery.post('<?=WPPLC_SHOP_PUB_DIR?>/includes/elementor/widgets/view/basket.php',{mode:'readonly'},function(retHTML) {
            jQuery('.plc-shop-basket').html(retHTML);
        })
    </script>
    <hr />
</form>