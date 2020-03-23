<?php
?>
<form method="POST" action="/<?=$sBasketSlug?>/confirm" class="plc-shop-form" style="display: inline-block; width:100%;">
    <div style="width:100%; float:left; padding:8px; background:transparent;">
        <ul style="list-style-type: none;" class="plcShopPayList">
            <?php if(count($oAPIResponse->paymentmethods) > 0) {
                $iPaymentSelected = ($oAPIResponse->paymentmethodselected->id != 0)
                    ? $oAPIResponse->paymentmethodselected->id : $oAPIResponse->paymentmethods[0]->id;
                foreach($oAPIResponse->paymentmethods as $oPayM) {
                    if($oPayM->gateway == 'instore') {
                        if($oAPIResponse->deliverymethod->gateway != 'pickup') {
                            continue;
                        }
                    }
                    ?>
                    <li>
                        <?php $sChecked = ($iPaymentSelected == $oPayM->id) ? ' checked="checked"' : ''; ?>
                        <input type="radio" name="shop_paymentmethod" value="<?=$oPayM->id?>" <?=$sChecked?>/>
                        <i class="<?=$oPayM->icon?>" style="width:60px;"></i> <?=$oPayM->label?>
                    </li>
                <?php
                    switch($oPayM->gateway) {
                        case 'stripe':
                            if(get_option('plcshop_gateway_stripe_enable_test') == 1) { ?>
                                <div class="elementor-widget-container">
                                    <div class="elementor-alert elementor-alert-danger" role="alert">
                                        <span class="elementor-alert-title">Stripe im Testmodus</span>
                                        <span class="elementor-alert-description">Achtung, Stripe befindet sich im Testmodus. Zahlungen sind nicht echt.</span>
                                        <button type="button" class="elementor-alert-dismiss">
                                            <span aria-hidden="true">×</span>
                                            <span class="elementor-screen-only">Warnung verwerfen</span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                            break;
                        default:
                            break;
                    }
                }
            } ?>
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