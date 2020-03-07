<div style="width:100%; display: inline-block;">
    <div class="plc-shop-featured-product-image" style="background:url(<?=$sHost?><?=$oItem->featured_image?>) no-repeat; background-size:cover;">

    </div>

    <div style="width:100%; display: inline-block;" class="plc-shop-featured-product-desc-box">
        <?php if($aSettings['featured_product_show_desc'] == 'yes') { ?>
            <div class="plc-shop-featured-product-desc" style="width: 100%; float:left; line-height:1.2 !important;">
                <p><?=$oItem->description?></p>
            </div>
        <?php } ?>

        <?php if($aSettings['featured_custom_amount_active'] == 'yes') { ?>
            <div style="width:50%; float:left; padding-right:8px; margin-top:8px;">
                <?php if(count($oItem->variants) > 0) { ?>
                    <select style="height:42px;" class="plc-art-price">
                        <?php foreach($oItem->variants as $oVar) { ?>
                            <option value="<?=$oVar->id?>">
                                <?=$oVar->label?> <?=number_format($oVar->price,2,',','.')?> €
                            </option>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div>
            <div style="width:50%; float:left; margin-top:8px;">
                <input class="plc-shop-featured-product-custom-amount" style="border:0; height:42px; border-radius: 0;" placeholder="Individueller Betrag" type="number" min="<?=$aSettings['featured_custom_amount_min']?>" max="<?=$aSettings['featured_custom_amount_max']?>"  step="<?=$aSettings['featured_custom_amount_steps']?>" />
            </div>
        <?php } else { ?>
            <div style="width:100%; float:left;" class="plc-shop-featured-product-desc-box">
                <?php if(count($oItem->variants) > 0) { ?>
                    <select style="height:42px;" class="plc-art-price">
                        <?php foreach($oItem->variants as $oVar) { ?>
                            <option value="<?=$oVar->id?>">
                                <?=$oVar->label?> <?=number_format($oVar->price,2,',','.')?> €
                            </option>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php if($aSettings['featured_custom_text_active'] == 'yes') { ?>
        <div style="width:100%; display: inline-block;" class="plc-shop-featured-product-desc-box">
            <textarea class="plc-shop-featured-product-description" style="border:0; border-radius: 0;" placeholder="Persönliche Widmung (max. 30 Wörter)"></textarea>
        </div>
    <?php } ?>

    <!-- Slide Buttons -->
    <div style="width:100%; display: inline-block;">
        <?php if($aSettings['btn1_active'] == 'yes') { ?>
        <?php $iWidth = ($aSettings['btn2_active'] == 'yes') ? '50%' : '100%'; ?>
        <div style="width:<?=$iWidth?>; float:left;">
            <!-- Buy Button -->
            <a href="#<?=$oItem->id?>" class="plc-shop-article-addtobasket plc-slider-button">
                <i class="<?=$aSettings['btn1_selected_icon']['value']?>" aria-hidden="true"></i>
                &nbsp;<?=$aSettings['btn1_text']?>
            </a>
            <!-- Buy Button -->
        </div>
        <?php } ?>
        <?php if($aSettings['btn2_active'] == 'yes') { ?>
            <div style="width:50%; float:left; padding-left:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>;">
                <!-- Gift Button -->
                <a href="#<?=$oItem->id?>" class="plc-shop-article-gifttobasket plc-slider-button">
                    <i class="<?=$aSettings['btn2_selected_icon']['value']?>" aria-hidden="true"></i>
                    &nbsp;<?=$aSettings['btn2_text']?>
                </a>
                <!-- Gift Button -->
            </div>
        <?php } ?>
    </div>
    <!-- Slide Buttons -->
</div>