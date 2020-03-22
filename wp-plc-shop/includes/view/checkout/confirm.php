<form action="/<?=$sBasketSlug?>/pay" method="POST" class="plc-shop-form">
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
        <?php if($oInfo->deliverymethod->gateway == 'mail') { ?>
            <?=$oInfo->contact->address->street?><br/>
            <?=$oInfo->contact->address->zip?> <?=$oInfo->contact->address->city?><br/>
        <?php } ?>
        <?=$oInfo->contact->email_private?><br/>
        <?=$oInfo->contact->phone_private?><br/>
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
    <?php
    $aItems = $oInfo->positions;
    if (count($aItems) > 0) { ?>
        <table class="plc-shop-form plc-shop-basket-table">
            <thead>
            <tr>
                <th style="width:120px">Bild</th>
                <th style="width:50%;">Artikel</th>
                <th style="text-align: right;">Preis</th>
                <th style="text-align: right;">Anz.</th>
                <th style="text-align: right;">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $dBasketTotal = 0;
            $dDeliveryFee = 2.5;
            foreach ($aItems as $oItem) {
                $dPrice = $oItem->price;
                switch ($oItem->article_type) {
                    case 'event':
                        $sImgPath = $oItem->oEvent->featured_image;
                        $dPrice = $oItem->oVariant->price;
                        $sPositionLabel = $oItem->oEvent->label.' am '.date('d.m.Y',strtotime($oItem->oEvent->date_start));
                        $sPositionLabel .= '<br/><small>Event Tickets: '.$oItem->oVariant->label.'</small>';
                        break;
                    case 'variant':
                        $sImgPath = $oItem->oArticle->featured_image;
                        $dPrice = $oItem->oVariant->price;
                        $sPositionLabel = $oItem->oArticle->label . ': ' . $oItem->oVariant->label;
                        break;
                    case 'article':
                    case 'custom':
                        $sImgPath = $oItem->oArticle->featured_image;
                        $sPositionLabel = $oItem->oArticle->label;
                        break;
                    default:
                        break;
                }?>
                <tr data-pos-id="<?=$oItem->id?>">
                    <td data-title="">
                        <div class="plc-shop-basket-pos-img"
                             style="background:url('<?= $sHost ?><?= $sImgPath ?>') no-repeat 100% 50%; background-size:cover;">
                            &nbsp;
                        </div>
                    </td>
                    <td data-title="Artikel:">
                        <?php
                        echo $sPositionLabel;
                        ?>
                        <?php if($oItem->comment != '') { ?>
                            <br/><small>Geschenkgutschein - Widmung: <?=$oItem->comment?></small>
                        <?php } ?>
                    </td>
                    <td data-title="Preis:" style="text-align: right;"><?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dPrice, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?></td>
                    <td data-title="Anzahl:" style="text-align: right;">
                        <?= $oItem->amount ?>
                    </td>
                    <td data-title="Total:" style="text-align: right;"><?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dPrice * $oItem->amount, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?></td>
                </tr>
                <?php
                $dBasketTotal += ($dPrice * $oItem->amount);
            } ?>
            </tbody>
        </table>

        <div style="width:100%; display: inline-block;">
            <?php
            $dDeliveryFee = 0;
            if($oInfo->deliverymethod->gateway == 'mail') {
                if ($dBasketTotal >= 100) {
                    $dDeliveryFee = 0;
                } else {
                    $dDeliveryFee = 2.5;
                }
            }
            ?>
            <div style="float:right;" class="plc-shop-form plc-shop-basket-summary">
                <h4>Warenkorb Summe</h4>
                <table>
                    <tbody>
                    <tr>
                        <th>Zwischensumme</th>
                        <td style="min-width:160px;" data-title="Zwischensumme">
                            <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dBasketTotal, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                        </td>
                    </tr>
                    <?php if($oInfo->deliverymethod->gateway == 'mail') { ?>
                    <tr>
                        <th>Versand (Postversand unter 100 €)</th>
                        <td data-title="Versand">
                            <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dDeliveryFee, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>Gesamtsumme</th>
                        <td data-title="Gesamtsumme">
                            <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dBasketTotal + $dDeliveryFee, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php if($oInfo->paymentmethod->gateway == 'paypal') {
                    if(get_option( 'plcshop_gateway_paypal_enable_test') == 1) { ?>
                    <script src="https://www.paypal.com/sdk/js?client-id=<?=get_option('plcshop_gateway_paypal_clientid_test')?>"></script>
                <?php } else { ?>
                    <script src="https://www.paypal.com/sdk/js?client-id=<?=get_option('plcshop_gateway_paypal_clientid_live')?>"></script>
                <?php } ?>
                    <div>
                    <div id="paypal-button-container"></div>
                    <script>
                        paypal.Buttons({
                            createOrder: function(data, actions) {
                                // This function sets up the details of the transaction, including the amount and line item details.
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: '<?=number_format($dBasketTotal,2,'.','')?>'
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                // This function captures the funds from the transaction.
                                return actions.order.capture().then(function(details) {
                                    jQuery.post(basketAjax.ajaxurl, {
                                        action: 'plc_showbasket',
                                        plc_basket_step: 'paypal'
                                    }, function (retHTML) {
                                        jQuery('.plc-shop-form').replaceWith(retHTML);
                                    });
                                });
                            }
                        }).render('#paypal-button-container');
                    </script>
                    </div>
                <?php } else { ?>
                <button type="submit" class="plc-shop-checkout-button" style="border:0;">
                    <i class="<?=str_replace(['fa-3x','fa-4x','fa-2x'],[],$oInfo->paymentmethod->icon)?>" style="width:20px;"></i>
                    Jetzt mit <?=$oInfo->paymentmethod->label?> bezahlen
                </button>
                <?php }
                switch($oInfo->paymentmethod->gateway) {
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
                        <?php }
                        break;
                    default:
                        break;
                }
                ?>
            </div>
        </div>
    <?php }
    ?>
    <hr />
</form>