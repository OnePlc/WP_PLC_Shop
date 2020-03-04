<h1>Slider</h1>
<?php
# Load Items we received from Shop server
$aItems = $oAPIResponse->results;

if(is_array($aItems)) {
    if(count($aItems) > 0) {
        foreach($aItems as $oItem) {
            ?>
            <div>
                <h3><?=$oItem->label?></h3>
                <a href="#<?=$oItem->id?>" plc-item-type="article" class="plc-shop-additem-tobasket">Buy</a>
            </div>
            <?php
            echo $oItem->label;
        }
    } else {
        echo 'NO ARTICLES FOR SLIDER';
    }
} else {
    echo 'NO ARTICLES FOR SLIDER';
}

?>
<script>
    jQuery('.plc-shop-additem-tobasket').on('click',function() {
        var iItemID = jQuery(this).attr('href').substring('#'.length);
        var sItemType = jQuery(this).attr('plc-item-type');

        addItemToBasket(iItemID,sItemType);

        return false;
    });
</script>
