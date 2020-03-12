<?php
?>
<div class="plc-shop-form">
    <h3>Vielen Dank für Ihre Bestellung</h3>
    <?=$aSettings['payment_prepay_infotext']?>
    <div class="elementor-widget-container">
        <div class="elementor-alert elementor-alert-info" role="alert">
            <span class="elementor-alert-title"><h4>Bankdaten für Vorkasse</h4></span>
            <span class="elementor-alert-description"><?=html_entity_decode(get_option('plcshop_gateway_prepay_bankacc'))?></span>
            <button type="button" class="elementor-alert-dismiss">
                <span aria-hidden="true">×</span>
                <span class="elementor-screen-only">Warnung verwerfen</span>
            </button>
        </div>
    </div>
</div>
