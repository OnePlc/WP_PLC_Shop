<?php
?>

<div style="width:100%; display: inline-block; text-align:center;">
    <h3><?=$oItem->label?></h3>
</div>
<div style="width:100%; display: inline-block; text-align:center;">
    <div style="margin:auto; display: inline-block;">
        <div style="width:20%; float:left; padding:2px; min-width:60px;">
            Anz
        </div>
        <div style="width:80%; float:left; padding:2px;">
            Article
        </div>
        <div style="width:20%; float:left; padding:2px; display: inline-block; min-width:60px;">
            <input id="plc-shop-popup-basket-amount" type="number" style="margin-bottom:8px; padding:12px; width:100%;" value="1" max="10" min="1" step="1">
        </div>
        <div style="width:80%; float:left; padding:2px; display: inline-block;">
            <?php
            /**
             * Article Variants Plugin - START
             */
            if (isset($oItem->variants)) { ?>
                <?php if (count($oItem->variants) > 0) { ?>
                    <select class="plc-slider-slide-price" id="plc-shop-popup-basket-article" plc-article-type="variant">
                        <?php foreach ($oItem->variants as $oVar) { ?>
                            <option value="<?= $oVar->id ?>">
                                <?= $oVar->label ?> - <?= $oVar->price ?>
                            </option>
                        <?php } ?>
                    </select>
                <?php } ?>
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
