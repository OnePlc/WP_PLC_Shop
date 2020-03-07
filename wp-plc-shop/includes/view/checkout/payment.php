<form method="POST" action="/<?=$sBasketSlug?>/confirm" class="plc-shop-form" style="display: inline-block; width:100%;">
    <div style="width:100%; float:left; padding:8px; background:transparent;">
        <ul style="list-style-type: none;" class="plcShopPayList">
            <?php
            if(get_option('plcshop_gateway_stripe_pk_test') || get_option('plcshop_gateway_stripe_pk_live')) {
            ?>
            <li>
                <?php $sChecked = ($iPaymentSelected == 1) ? ' checked="checked"' : ''; ?>
                <input type="radio" name="shop_paymentmethod" value="1" <?=$sChecked?>/>
                <i class="fab fa-cc-stripe fa-3x" style="width:60px;"></i> Stripe (Kreditkarte,Google Pay,Apple Pay)
            </li>
            <?php } ?>
            <!--
            Currently disabled - needs to be migrated to Shop V2
             -->
            <li>
                <?php $sChecked = ($iPaymentSelected == 2) ? ' checked="checked"' : ''; ?>
                <input type="radio" name="shop_paymentmethod" value="2" <?=$sChecked?> disabled />
                <i class="fab fa-paypal fa-3x" style="width:60px;"></i> Paypal (in Kürze verfügbar)
            </li>
            <li>
                <?php $sChecked = ($iPaymentSelected == 4) ? ' checked="checked"' : ''; ?>
                <input type="radio" name="shop_paymentmethod" value="4" <?=$sChecked?> />
                <i class="fas fa-university fa-3x" style="width:60px;"></i> Vorkasse (Banküberweisung)
            </li>
        </ul>
    </div>
    <div style="width:100%; float:left; padding:8px;">
        <button class="plc-shop-checkout-button" type="submit" style="border:0;">
            <?php if(!empty($aSettings['btn_payment_selected_icon'])) { ?>
            <i class="<?=$aSettings['btn_payment_selected_icon']['value']?>" style="width:20px;"></i>
            <?php } ?>
            <?=$aSettings['btn_payment_text']?>
        </button>
    </div>
</form>