<?php
$sTemplate = get_option('plcshop_basket_steps_style');

$sClass = 'cd-multi-steps text-center';
switch($sTemplate) {
    case 'msicons':
        $sClass = 'cd-multi-steps text-center';
        break;
    case 'dots':
        $sClass = 'cd-multi-steps text-top';
        break;
    case 'triangleicons':
        $sClass = 'cd-breadcrumb triangle';
        break;
    case 'triangle':
        $sClass = 'cd-breadcrumb triangle';
        break;
    default:
        break;
}

$sClassBasket = 'current';
$sClassDelivery = '';
$sClassPayment = '';
$sClassReview = '';

switch($sStep) {
    case 'address':
        $sClassBasket = 'visited';
        $sClassDelivery = 'current';
        break;
    case 'payment':
        $sClassBasket = 'visited';
        $sClassDelivery = 'visited';
        $sClassPayment = 'current';
        break;
    case 'confirm':
    case 'pay':
        $sClassBasket = 'visited';
        $sClassDelivery = 'visited';
        $sClassPayment = 'visited';
        $sClassReview = 'current';
        break;
    case 'cancel':
        $sClassBasket = 'visited';
        $sClassDelivery = 'visited';
        $sClassPayment = 'visited';
        $sClassReview = 'visited';
        break;
    default:
        break;
}
?>
<nav>
    <ol class="<?=$sClass?> plc-shop-breadcrumb-nav">
        <li class="<?=$sClassBasket?>">
            <a href="/<?=$sBasketSlug?>">
                <?php if($sTemplate == 'triangleicons' || $sTemplate == 'msicons') { ?>
                    <i class="<?=$aSettings['br_basket_selected_icon']['value']?>" aria-hidden="true"></i>
                <?php } ?>
                <?=$aSettings['br_basket_text']?>
            </a>
        </li>
        <li class="<?=$sClassDelivery?>">
            <?php if($sClassPayment != '') { ?>
            <a href="/<?=$sBasketSlug?>/address">
                <?php } else { ?>
                <em>
                    <?php } ?>
                    <?php if($sTemplate == 'triangleicons' || $sTemplate == 'msicons') { ?>
                        <i class="<?=$aSettings['br_delivery_selected_icon']['value']?>" aria-hidden="true"></i>
                    <?php } ?>
                    <?=$aSettings['br_delivery_text']?>
                    <?php if($sClassPayment != '') { ?>
            </a>
        <?php } else { ?>
            </em>
        <?php } ?>
        </li>
        <li class="<?=$sClassPayment?>">
            <?php if($sClassReview != '') { ?>
            <a href="/<?=$sBasketSlug?>/payment">
                <?php } else { ?>
                <em>
                    <?php } ?>
                    <?php if($sTemplate == 'triangleicons' || $sTemplate == 'msicons') { ?>
                        <i class="<?=$aSettings['br_payment_selected_icon']['value']?>" aria-hidden="true"></i>
                    <?php } ?>
                    <?=$aSettings['br_payment_text']?>
                    <?php if($sClassReview != '') { ?>
            </a>
        <?php } else { ?>
            </em>
        <?php } ?>
        </li>
        <li class="<?=$sClassReview?>">
            <em>
                <?php if($sTemplate == 'triangleicons' || $sTemplate == 'msicons') { ?>
                    <i class="<?=$aSettings['br_review_selected_icon']['value']?>" aria-hidden="true"></i>
                <?php } ?>
                <?=$aSettings['br_review_text']?>
            </em>
        </li>
    </ol>
</nav>