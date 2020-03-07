<?php
$iPaymentSelected = $oAPIResponse->paymentmethodselected->id;
?>
<form method="POST" action="/<?=$sBasketSlug?>/confirm" class="plc-shop-form" style="display: inline-block; width:100%;">
    <div style="width:100%; float:left; padding:8px; background:transparent;">
        <ul style="list-style-type: none;" class="plcShopPayList">
            <?php if(count($oAPIResponse->paymentmethods) > 0) {
                foreach($oAPIResponse->paymentmethods as $oPayM) { ?>
                    <li>
                        <?php $sChecked = ($iPaymentSelected == $oPayM->id) ? ' checked="checked"' : ''; ?>
                        <input type="radio" name="shop_paymentmethod" value="<?=$oPayM->id?>" <?=$sChecked?>/>
                        <i class="<?=$oPayM->icon?>" style="width:60px;"></i> <?=$oPayM->label?>
                    </li>
                <?php
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