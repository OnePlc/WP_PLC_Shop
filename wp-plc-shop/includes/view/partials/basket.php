<?php
$aItems = [];
if(isset($oAPIResponse->items)) {
    if(count($oAPIResponse->items) > 0) {
        $aItems = $oAPIResponse->items;
    }
}
$sBasketSlug = get_option('plcshop_basket_slug');
?>
<?php
if (count($aItems) > 0) { ?>
    <table class="plc-shop-form plc-shop-basket-table">
        <thead>
        <tr>
            <?php if($sMode == 'default') { ?>
                <th>&nbsp;</th>
            <?php } ?>
            <th style="width:120px"><?=__('Bild', 'wp-plc-shop')?></th>
            <th style="width:50%;"><?=__('Artikel', 'wp-plc-shop')?></th>
            <th style="text-align:right;"></th>
            <th style="text-align:right;"><?=__('Anz.', 'wp-plc-shop')?></th>
            <th style="text-align:right;"><?=__('Total', 'wp-plc-shop')?></th>
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
                <?php if($sMode == 'default') { ?>
                    <td data-title="Entfernen:">
                        <a href="#" class="plc-shop-basket-pos-del">
                            <i class="fas fa-times"></i>
                        </a>
                    </td>
                <?php } ?>
                <td data-title="">
                    <div class="plc-shop-basket-pos-img"
                         style="background:url('<?= $sHost ?><?= $sImgPath ?>') no-repeat 100% 100%; background-size:cover;">
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
                <td data-title="Preis:" style="text-align:right;">
                    <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?>
                    <?= number_format($dPrice, 2, ',', '.') ?>
                    <?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                </td>
                <td data-title="Anzahl:" style="text-align:right;">
                    <?php if($sMode == 'readonly') { ?>
                        <?= $oItem->amount ?>
                    <?php } else { ?>
                        <input type="number" value="<?= $oItem->amount ?>" class="plc-shop-input plc-shop-basket-pos-amount" size="4" min="0" step="1" style="padding:8px; width:80px;" />
                    <?php } ?>
                </td>
                <td data-title="Total:" style="text-align:right;"><?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dPrice * $oItem->amount, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?></td>
            </tr>
            <?php
            $dBasketTotal += ($dPrice * $oItem->amount);
        } ?>
        </tbody>
    </table>

    <div style="width:100%; display: inline-block;">
        <?php
        /**
        if($dBasketTotal >= 100) {
            $dDeliveryFee = 0;
        } **/
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
                <!-- make it an option ...................
                <tr>
                    <th>Versand (nur bei Postversand)</th>
                    <td data-title="Versand">
                        <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dDeliveryFee, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                    </td>
                </tr> --->
                <tr>
                    <th>Gesamtsumme</th>
                    <td data-title="Gesamtsumme">
                        <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dBasketTotal, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php if($sMode == 'default') { ?>
                <a href="/<?=$sBasketSlug?>/address" class="plc-shop-checkout-button">
                    <?php if(!empty($aSettings['btn_checkout_selected_icon'])) { ?>
                        <i class="<?=$aSettings['btn_checkout_selected_icon']['value']?>" style="width:20px;"></i>
                    <?php } ?>
                    <?=$aSettings['btn_checkout_text']?>
                </a>
            <?php } else { ?>
                <a href="/<?=$sBasketSlug?>/pay" class="plc-shop-checkout-button">
                    <i class="<?=$oBasket->oPaymentmethod->icon?>" style="width:20px;"></i>
                    Jetzt mit <?=$oBasket->oPaymentmethod->label?> bezahlen
                </a>
            <?php } ?>
        </div>
    </div>
<?php } else { ?>
    <div class="elementor-widget-container">
        <div class="elementor-alert elementor-alert-info" role="alert">
            <span class="elementor-alert-title"><b>Warenkorb leer</b></span>
            <span class="elementor-alert-description">Es befindet sich aktuell noch nichts im Warenkorb.</b></span>
        </div>
    </div>
<?php } ?>
<?php if(is_user_logged_in() && get_option( 'plcshop_elementor_basket_debugmode') == 1) { ?>
    <div class="elementor-widget-container">
        <div class="elementor-alert elementor-alert-info" role="alert">
            <span class="elementor-alert-title">Debug Info für Wordpress Admins</span>
            <span class="elementor-alert-description">Wordpress Shop Session ID: <b><?=session_id();?></b></span>
            <button type="button" class="elementor-alert-dismiss">
                <span aria-hidden="true">×</span>
                <span class="elementor-screen-only">Warnung verwerfen</span>
            </button>
        </div>
    </div>
<?php } ?>