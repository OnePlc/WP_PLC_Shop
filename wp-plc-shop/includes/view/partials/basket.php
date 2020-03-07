<?php
$aItems = [];
if(isset($oAPIResponse->items)) {
    if(count($oAPIResponse->items) > 0) {
        $aItems = $oAPIResponse->items;
    }
}
$sBasketSlug = get_option('plcshop_basket_slug');
echo session_id();
if (count($aItems) > 0) { ?>
    <table class="plc-shop-form plc-shop-basket-table">
        <thead>
        <tr>
            <?php if($sMode == 'default') { ?>
                <th>&nbsp;</th>
            <?php } ?>
            <th style="width:120px">Bild</th>
            <th>Artikel</th>
            <th>Preis</th>
            <th>Anz.</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $dBasketTotal = 0;
        $dDeliveryFee = 2.5;
        foreach ($aItems as $oItem) {
            $dPrice = $oItem->price; ?>
            <tr data-pos-id="<?=$oItem->id?>">
                <?php if($sMode == 'default') { ?>
                    <td data-title="Entfernen:">
                        <i class="fas fa-times plc-shop-basket-pos-del"></i>
                    </td>
                <?php } ?>
                <td data-title="">
                    <div class="plc-shop-basket-pos-img"
                         style="background:url(<?= $sHost ?>/data/article/<?= $oItem->article_idfs ?>/avatar.png) no-repeat 100% 50%; background-size:cover;">
                        &nbsp;
                    </div>
                </td>
                <td data-title="Artikel:">
                    <?php
                    switch ($oItem->article_type) {
                        case 'event':
                            echo $oItem->oEvent->label.' am '.date('d.m.Y',strtotime($oItem->oEvent->date_start));
                            echo '<br/><small>'.$oItem->oArticle->label.'</small>';
                            break;
                        case 'variant':
                            $dPrice = $oItem->oVariant->price;
                            echo $oItem->oArticle->label . ': ' . $oItem->oVariant->label;
                            break;
                        case 'article':
                        case 'custom':
                            echo $oItem->oArticle->label;
                            break;
                        default:
                            break;
                    }
                    ?>
                    <?php if($oItem->comment != '') { ?>
                        <br/><small>Geschenkgutschein - Widmung: <?=$oItem->comment?></small>
                    <?php } ?>
                </td>
                <td data-title="Preis:"><?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dPrice, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?></td>
                <td data-title="Anzahl:">
                    <?php if($sMode == 'readonly') { ?>
                        <?= $oItem->amount ?>
                    <?php } else { ?>
                        <input type="number" value="<?= $oItem->amount ?>" class="plc-shop-input plc-shop-basket-pos-amount" size="4" min="0" step="1" style="padding:8px; width:80px;" />
                    <?php } ?>
                </td>
                <td data-title="Total:"><?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dPrice * $oItem->amount, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?></td>
            </tr>
            <?php
            $dBasketTotal += ($dPrice * $oItem->amount);
        } ?>
        </tbody>
    </table>

    <div style="width:100%; display: inline-block;">
        <?php
        if($dBasketTotal >= 100) {
            $dDeliveryFee = 0;
        }
        ?>
        <div style="float:right;" class="plc-shop-form plc-shop-basket-summary">
            <h4>Warenkorb Summe</h4>
            <table>
                <tbody>
                <tr>
                    <th>Zwischensumme</th>
                    <td data-title="Zwischensumme">
                        <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dBasketTotal, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                    </td>
                </tr>
                <tr>
                    <th>Versand (nur bei Postversand)</th>
                    <td data-title="Versand">
                        <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dDeliveryFee, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
                    </td>
                </tr>
                <tr>
                    <th>Gesamtsumme</th>
                    <td data-title="Gesamtsumme">
                        <?=(get_option('plcshop_currency_pos') == 'before') ? get_option('plcshop_currency_main').' ' : ''?><?= number_format($dBasketTotal + $dDeliveryFee, 2, ',', '.') ?><?=(get_option('plcshop_currency_pos') == 'after') ? ' '.get_option('plcshop_currency_main') : ''?>
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
    <div>
        Warenkorb leer
    </div>
<?php }?>