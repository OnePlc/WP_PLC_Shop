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
    <?php
    $aItems = $oInfo->positions;
    if (count($aItems) > 0) { ?>
        <table class="plc-shop-form plc-shop-basket-table">
            <thead>
            <tr>
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
                        <?= $oItem->amount ?>
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
                <a href="/<?=$sBasketSlug?>/pay" class="plc-shop-checkout-button">
                    <i class="<?=str_replace(['fa-3x','fa-4x','fa-2x'],[],$oInfo->paymentmethod->icon)?>" style="width:20px;"></i>
                    Jetzt mit <?=$oInfo->paymentmethod->label?> bezahlen
                </a>
            </div>
        </div>
    <?php }
    ?>
    <hr />
</form>