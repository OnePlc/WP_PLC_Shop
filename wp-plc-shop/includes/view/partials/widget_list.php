<div style="width:100%; display: inline-block;">
    <?php
    $sLastMonth = '';
    $iCount = 0;
    $iBaseWidth = 150;

    foreach ($aEvents as $oEvent) {
        if ($iCount == $aSettings['compactlist_list_limit']) {
            break;
        }

        $aVars = $oEvent->variants;
        foreach($oEvent->variants as $oVar) {
            ?>
            <div class="plc-calendar-list-item" style="width:100%; min-height:75px; float:left; height:auto;">
                <div style="display: inline-block; width:100%;">
                    <div class="plc-calendar-list-widget-title plc-shop-list-title-box"
                         style="float:left; min-height:75px;">
                        <?= $oEvent->label ?><br/>
                        <span><?= $oEvent->description ?></span>
                    </div>
                    <div class="plc-shop-list-button-panel" style="float:left; margin-left:4px; display: inline-block;">
                        <span class="plc-calendar-widget-button plc-responsive-btn-50" style="float:left; padding-right:4px;">
                            <?= ($oVar->label != '') ? $oVar->label : '-' ?>
                        </span>
                        <span class="plc-calendar-widget-button plc-responsive-btn-50" style="float:left;">
                            <?= number_format($oVar->price,2,',','.') ?>
                        </span>
                        <a class="plc-shop-additem-tobasket plc-calendar-widget-button"
                           href="#<?= $oEvent->id ?>"
                           style="display:inline-block; margin-top:4px; padding-top:4px; float:left; width:100%; height:31px; text-align:center;"
                           plc-item-type="variant" plc-item-variant="<?= $oVar->id ?>">
                            <i class="<?= $aSettings['btn1_selected_icon']['value'] ?>" aria-hidden="true"></i>
                            &nbsp;<label class="elementor-hidden-phone elementor-hidden-tablet" style="cursor:pointer;"><?= $aSettings['btn1_text'] ?></label>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            $iCount++;
        }
    }
    ?>
</div>