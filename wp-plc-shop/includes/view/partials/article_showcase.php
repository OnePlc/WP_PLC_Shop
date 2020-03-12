<?php
//$sBuyCls = ($aSettings['slider_slide_show_popup_basket'] == 'yes') ? 'plc-shop-additem-tobasket' : 'plc-shop-additemwithamount-tobasket';
$sBuyCls = 'plc-shop-additem-tobasket';
$sCurrency = get_option('plcshop_currency_main');
$sDecPoint = ($sCurrency == '€') ? ',' : '.';
$sThsndSep = ($sCurrency == '€') ? '.' : '\'';

foreach($aArticles as $oArt) {
    $sClass = (++$iCount%2?"odd":"even");
    ?><div class="plc-art-showcase-big-box box-<?=$sClass?>" style="width:100%; display:inline-block; text-align: center;">
    <div style="width:100%; max-width:1140px; display: inline-block; margin:auto; text-align: left;">
        <div class="plc-art-showcase-big-img" style="">
            <a href="<?=$sHost?><?=$oArt->featured_image?>" data-elementor-open-lightbox="yes">
                <div style="background:url('<?=$sHost?><?=$oArt->featured_image?>') no-repeat 100% 50%; background-size:cover;">
                    &nbsp;
                </div>
            </a>
        </div>
        <div  class="plc-art-showcase-big-content">
            <h2 class="plc-art-showcase-big-title box-<?=$sClass?>"><?=$oArt->label?></h2>
            <?php if(isset($oArt->variants)) { ?>
                <select style="border:1px solid #333; border-radius: 0;" class="plc-art-price">
                    <?php foreach($oArt->variants as $oVar) {
                        $sVarLabel = $oVar->label.' - '.$sCurrency.' '.number_format($oVar->price,2,$sDecPoint,$sThsndSep);
                        if(get_option('plcshop_currency_pos') == 'after') {
                            $sVarLabel = $oVar->label.' - '.number_format($oVar->price,2,$sDecPoint,$sThsndSep).' '.$sCurrency;
                        }?>
                        <option value="<?=$oVar->id?>"><?=$sVarLabel?></option>
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
                    <a href="#<?=$oArt->id?>" class="<?=$sBuyCls?> plc-slider-button" plc-item-type="<?=(isset($oArt->variants)) ? 'variant' : 'article'?>" style="display: inline-block; width:100%;">
                        <i class="<?=$aSettings['btn1_selected_icon']['value']?>" aria-hidden="true"></i>
                        &nbsp;<?=$aSettings['btn1_text']?>
                    </a>
                    <!-- Buy Button -->
                </div>
                <div style="width:33%; float:left; padding-right:8px;">
                    <!-- Gift Button -->
                    <a href="#<?=$oArt->id?>" class="plc-shop-giftitem-tobasket plc-slider-button" plc-item-type="<?=(isset($oArt->variants)) ? 'variant' : 'article'?>" style="display: inline-block; width:100%;">
                        <i class="<?=$aSettings['btn2_selected_icon']['value']?>" aria-hidden="true"></i>
                        &nbsp;<?=$aSettings['btn2_text']?>
                    </a>
                    <!-- Gift Button -->
                </div>
                <div style="width:33%; float:left;">
                    <!-- E-Mail Button -->
                    <a href="#<?=$oArt->id?>" class="plc-shop-article-request plc-slider-button" style="display: inline-block; width:100%;">
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