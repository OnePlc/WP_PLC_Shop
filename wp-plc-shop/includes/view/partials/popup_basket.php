<?php
$sCurrency = get_option('plcshop_currency_main');
$sDecPoint = ($sCurrency == '€') ? ',' : '.';
$sThsndSep = ($sCurrency == '€') ? '.' : '\'';
?>
<div class="plc-shop-popup-content">
    <div style="width:100%; display: inline-block; text-align:center;">
        <h3 style=" font-size:22px;">
            <?php
            switch($sItemType) {
                case 'article':
                case 'variant':
                    if(strlen($oItem->label) > 50) {
                        echo substr($oItem->label,0,50).'..';
                    } else {
                        echo $oItem->label;
                    }
                    break;
                case 'event':
                    echo $oItem->label;
                    echo '<br/><small>'. utf8_encode(strftime('%A, %d. %B %Y', strtotime($oItem->date_start))) .'</small>';
                    break;
                default:
                    break;
            }
            ?>
        </h3>
    </div>
    <div style="width:100%; display: inline-block; text-align:center;">
        <div style="margin:auto; display: inline-block;">
            <div style="width:22%; float:left; padding:2px; min-width:60px;">
                Anz
            </div>
            <div style="width:76%; float:left; padding:2px;">
                <?php
                switch($sItemType) {
                    case 'article':
                    case 'variant':
                        echo __('Article', 'wp-plc-shop');
                        echo '<input type="hidden" id="plc-shop-popup-basket-ref-label" value="'.str_replace(["\""],["'"],$oItem->label).'" />';
                        break;
                    case 'event':
                        echo __('Event - Ticket', 'wp-plc-shop');
                        echo '<input type="hidden" id="plc-shop-popup-basket-ref-idfs" value="'.$oItem->id.'" />';
                        echo '<input type="hidden" id="plc-shop-popup-basket-ref-type" value="event" />';
                        echo '<input type="hidden" id="plc-shop-popup-basket-ref-label" value="'.$oItem->label.'" />';
                        break;
                    default:
                        break;
                }
                ?>
            </div>
            <div style="width:22%; float:left; padding:2px; display: inline-block; min-width:60px;">
                <input id="plc-shop-popup-basket-amount" type="number" style="margin-bottom:8px; padding:12px; width:100%;" value="1" max="10" min="1" step="1">
            </div>
            <div style="width:76%; float:left; padding:2px; display: inline-block;">
                <?php
                /**
                 * Article Variants Plugin - START
                 */
                if (isset($oItem->variants)) { ?>
                    <?php if (count($oItem->variants) > 0) { ?>
                        <select class="plc-slider-slide-price" id="plc-shop-popup-basket-article" plc-article-type="variant">
                            <?php foreach ($oItem->variants as $oVar) {
                                $sVarLabel = $oVar->label.' - '.$sCurrency.' '.number_format($oVar->price,2,$sDecPoint,$sThsndSep);
                                if(get_option('plcshop_currency_pos') == 'after') {
                                    $sVarLabel = $oVar->label.' - '.number_format($oVar->price,2,$sDecPoint,$sThsndSep).' '.$sCurrency;
                                }
                                $sSel = ($iVariantID == $oVar->id) ? ' selected="selected"' : '';
                                ?>
                                <option value="<?= $oVar->id ?>"<?=$sSel?>>
                                    <?= $sVarLabel ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                <?php } elseif (isset($oItem->tickets)) { ?>
                    <select class="plc-slider-slide-price" id="plc-shop-popup-basket-article" plc-article-type="variant">
                        <?php foreach ($oItem->tickets as $oVar) {
                            $sVarLabel = $oVar->label.' - '.$sCurrency.' '.number_format($oVar->price,2,$sDecPoint,$sThsndSep);
                            if(get_option('plcshop_currency_pos') == 'after') {
                                $sVarLabel = $oVar->label.' - '.number_format($oVar->ticket_price,2,$sDecPoint,$sThsndSep).' '.$sCurrency;
                            }?>
                            <option value="<?= $oVar->id ?>">
                                <?= $sVarLabel ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } elseif(isset($oItem->price_sell)) { ?>
                    <select class="plc-slider-slide-price" id="plc-shop-popup-basket-article" plc-article-type="article">
                        <option value="<?= $oItem->id ?>">
                            <?= $oItem->label ?> - <?= $oItem->price_sell ?>
                        </option>
                    </select>
                    <?php
                }
                /**
                 * Article Variants Plugin - END
                 */
                ?>
            </div>
        </div>
    </div>
</div>