<?php
//$sBuyCls = ($aSettings['slider_slide_show_popup_basket'] == 'yes') ? 'plc-shop-additem-tobasket' : 'plc-shop-additemwithamount-tobasket';
$sBuyCls = 'plc-shop-additem-tobasket';

foreach($aArticles as $oArt) {
    $sClass = (++$iCount%2?"odd":"even");
    ?><div class="plc-art-showcase-big-box box-<?=$sClass?>" style="width:100%; display:inline-block; text-align: center;">
    <div style="width:100%; max-width:1140px; display: inline-block; margin:auto; text-align: left;">
        <div class="plc-art-showcase-big-img" style="">
            <a href="<?=$sHost?>/data/article/<?=$oArt->id?>/avatar.png" data-elementor-open-lightbox="yes">
                <div style="background:url(<?=$sHost?>/data/article/<?=$oArt->id?>/avatar.png) no-repeat 100% 50%; background-size:cover;">
                    &nbsp;
                </div>
            </a>
        </div>
        <div  class="plc-art-showcase-big-content">
            <h2 class="plc-art-showcase-big-title box-<?=$sClass?>"><?=$oArt->label?></h2>
            <?php if(isset($oArt->variants)) { ?>
                <select style="border:1px solid #333; border-radius: 0;" class="plc-art-price">
                    <?php foreach($oArt->variants as $oVar) { ?>
                        <option value="<?=$oVar->id?>"><?=$oVar->label?> <?=number_format($oVar->price_sell,2,',','.')?> €</option>
                    <?php } ?>
                </select>
            <?php } else { ?>
                <select style="border:1px solid #333; border-radius: 0;" class="plc-art-price">
                    <option value="<?=$oArt->id?>">
                        <?=number_format($oArt->price_sell,2,',','.')?> €
                    </option>
                </select>
            <?php } ?>
            <div class="plc-art-showcase-big-description">
                <?=$oArt->description?>
            </div>
            <div style="width:100%; display: inline-block; margin-top:12px;">
                <div style="width:33%; float:left; padding-right:8px;">
                    <!-- Buy Button -->
                    <a href="#<?=$oArt->id?>" class="<?=$sBuyCls?> plc-slider-button" plc-item-type="<?=(isset($oArt->variants)) ? 'variant' : 'article'?>">
                        <i class="<?=$aSettings['btn1_selected_icon']['value']?>" aria-hidden="true"></i>
                        &nbsp;<?=$aSettings['btn1_text']?>
                    </a>
                    <!-- Buy Button -->
                </div>
                <div style="width:33%; float:left; padding-right:8px;">
                    <!-- Gift Button -->
                    <a href="#<?=$oArt->id?>" class="plc-shop-giftitem-tobasket plc-slider-button" plc-item-type="<?=(isset($oArt->variants)) ? 'variant' : 'article'?>">
                        <i class="<?=$aSettings['btn2_selected_icon']['value']?>" aria-hidden="true"></i>
                        &nbsp;<?=$aSettings['btn2_text']?>
                    </a>
                    <!-- Gift Button -->
                </div>
                <div style="width:33%; float:left;">
                    <!-- E-Mail Button -->
                    <a href="#<?=$oArt->id?>" class="plc-shop-article-request plc-slider-button" style="font-size:16px; font-family:<?=get_option('plcshop_popup_emailbutton_fontfamily')?>; text-transform:<?=get_option('plcshop_popup_emailbutton_transform')?>; padding:8px; color:<?=get_option('plcshop_popup_emailbutton_color')?>; background: <?=get_option('plcshop_popup_emailbutton_background')?>;">
                        <i class="<?=get_option('plcshop_popup_emailbutton_icon')?>" aria-hidden="true" style="color:<?=get_option('plcshop_popup_emailbutton_iconcolor')?>;"></i>
                        &nbsp;<?=get_option('plcshop_popup_emailbutton_text')?>
                    </a>
                    <!-- E-Mail Button -->
                </div>
            </div>
        </div>
    </div>
    </div>
<?php } ?>