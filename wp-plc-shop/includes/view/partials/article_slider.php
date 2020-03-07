<!-- PLC Shop Article Slider -->
<div class="plc-shop-swiper-container swiper-container" id="plc-slider-<?=$sSliderID?>" data-slides-per-view="<?=$aSettings['slider_slides_per_view']?>">
    <!-- Swiper Slider -->
    <div class="swiper-wrapper">
    <?php
    # Load Items we received from Shop server
    $aItems = $oAPIResponse->results;

    if(is_array($aItems)) {
        if(count($aItems) > 0) {
            foreach($aItems as $oItem) { ?>
            <!-- Slide -->
            <div class="swiper-slide">
                <div style="display: inline-block; width:100%;">
                    <?php if($aSettings['slider_slide_show_featuredimage'] == "yes") { ?>
                    <!-- Slide Image -->
                    <figure class="plc-slider-image-box-img" style="width:100%; margin-bottom: <?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>;">
                        <a href="#<?=$oItem->id?>" class="plc-shop-article-popup" title="Mehr Informationen">
                            <div style="height:200px; width:100%; min-width:100%; background:url(<?=$sHost?>/data/article/<?=$oItem->id?>/avatar.png) no-repeat 100% 50%; background-size:cover;">
                                &nbsp;
                            </div>
                        </a>
                    </figure>
                    <!-- Slide Image -->
                    <?php } ?>
                    <!-- Slide Content -->
                    <div class="plc-slider-slide-content" style="width:100%; text-align:center; margin-top:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>; margin-bottom:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>; min-height:200px;">
                        <?php if($aSettings['slider_slide_show_title'] == 'yes') { ?>
                        <!-- Title -->
                        <div style="height:<?=$aSettings['slider_slide_title_height']?>; position: relative; overflow:hidden; vertical-align: middle;">
                            <a href="#<?=$oItem->id?>" class="plc-shop-article-popup" title="Mehr Informationen">
                                <h3 class="plc-slider-slide-title" style="display: inline-block; width:100%; padding-top:6px; vertical-align:middle; text-align:<?=$aSettings['slider_slide_title_align']?>;">
                                    <?= $oItem->label ?>
                                </h3>
                            </a>
                        </div>
                        <!-- Title -->
                        <?php } ?>

                        <?php if($aSettings['slider_slide_show_description'] == 'yes') { ?>
                        <!-- Description -->
                        <div class="plc-slider-slide-description" style="height:<?=$aSettings['slider_slide_desc_height']?>; position: relative; overflow:hidden; vertical-align: middle; text-align:<?=$aSettings['slider_slide_title_align']?>;">
                            <?= $oItem->description ?>
                        </div>
                        <!-- Description -->
                        <?php } ?>

                        <?php if($aSettings['slider_slide_show_price'] == 'yes') {
                            if($aSettings['slider_slide_show_popup_basket'] != 'yes') { ?>
                                <div style="width:100%; display: inline-block;">
                                    <div style="width:30%; float:left;">
                                        <input type="number" value="1" class="plc-slider-slide-amount" />
                                    </div>
                                    <div style="width:70%; float:left;">
                            <?php
                            } else { ?>
                                <div style="width:100%;">
                                    <div style="width:100%;">
                                        <?php
                            }
                            /**
                             * Article Variants Plugin - START
                             */
                            if (isset($oItem->variants)) { ?>
                                <?php if (count($oItem->variants) > 0) { ?>
                                    <select class="plc-slider-slide-price" plc-item-type="variant">
                                        <?php foreach ($oItem->variants as $oVar) { ?>
                                            <option value="<?= $oVar->id ?>">
                                                <?= $oVar->label ?> - <?= $oVar->price ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            <?php } elseif(isset($oItem->price_sell)) { ?>
                                <select class="plc-slider-slide-price" plc-item-type="article">
                                    <option value="<?= $oItem->id ?>">
                                        <?= $oItem->label ?> - <?= $oItem->price_sell ?>
                                    </option>
                                </select>
                            <?php
                            }
                            /**
                             * Article Variants Plugin - END
                             */
                        }
                        ?>
                            </div>
                         </div>

                        <!-- Slide Buttons -->
                        <div style="width:100%; display: inline-block;">
                            <?php if($aSettings['slider_slide_show_button_buy'] == 'yes') { ?>
                                <div style="width:50%; float:left;">
                                    <!-- Buy Button -->
                                    <?php $sBuyCls = ($aSettings['slider_slide_show_popup_basket'] == 'yes') ? 'plc-shop-additem-tobasket' : 'plc-shop-additemwithamount-tobasket'?>
                                    <a href="#<?=$oItem->id?>" class="<?=$sBuyCls?> plc-slider-button" plc-item-type="<?=(isset($oItem->variants)) ? 'variant' : 'article'?>" style="display:inline-block; width:<?=$aSettings['buttons_slide_width']?>;">
                                        <i class="<?=$aSettings['btn1_selected_icon']['value']?>" aria-hidden="true"></i>
                                        &nbsp;<?=$aSettings['btn1_text']?>
                                    </a>
                                    <!-- Buy Button -->
                                </div>
                            <?php } ?>

                            <?php if($aSettings['slider_slide_show_button_gift'] == 'yes') { ?>
                                <div style="width:50%; float:left; padding-left:<?=$aSettings['slider_item_spacer']['size'].$aSettings['slider_item_spacer']['unit']?>;">
                                    <!-- Gift Button -->
                                    <a href="#<?=$oItem->id?>" class="plc-shop-giftitem-tobasket plc-slider-button" plc-item-type="<?=(isset($oItem->variants)) ? 'variant' : 'article'?>" style="display:inline-block; width:<?=$aSettings['buttons_slide_width']?>;">
                                        <i class="<?=$aSettings['btn2_selected_icon']['value']?>" aria-hidden="true"></i>
                                        &nbsp;<?=$aSettings['btn2_text']?>
                                    </a>
                                    <!-- Gift Button -->
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Slide Buttons -->
                    </div>
                    <!-- Slide Content -->
                </div>
            </div>
        <?php
            }
        } else {
            echo 'NO ARTICLES FOR SLIDER';
        }
    } else {
        echo 'NO ARTICLES FOR SLIDER';
    }

    ?>
    </div>
</div>