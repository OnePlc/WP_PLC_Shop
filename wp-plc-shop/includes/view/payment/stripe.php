<?php
/**
 * Set Options for Stripe API
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ERROR);

$sWPUrl = get_site_url();
$sBasketSlug = get_option('plcshop_basket_slug');

$sApiKey = (get_option('plcshop_gateway_stripe_enable_test') == 1) ? get_option('plcshop_gateway_stripe_sk_test') : get_option('plcshop_gateway_stripe_sk_live');
$sPubKey = (get_option('plcshop_gateway_stripe_enable_test') == 1) ? get_option('plcshop_gateway_stripe_pk_test') : get_option('plcshop_gateway_stripe_pk_live');

// Set Stripe API Key
\Stripe\Stripe::setApiKey($sApiKey);
?>
    <div class="elementor-widget-container">
        <div class="elementor-alert elementor-alert-info" role="alert">
            <span class="elementor-alert-title"><b>Bitte Warten</b></span>
            <span class="elementor-alert-description">Sie werden zur Bezahlseite auf stripe.com weitergeleitet.</span>
            <button type="button" class="elementor-alert-dismiss">
                <span aria-hidden="true">×</span>
                <span class="elementor-screen-only">Warnung verwerfen</span>
            </button>
        </div>
    </div>

<?php

if(!function_exists('getStripeCurrencyCode')) {
    function getStripeCurrencyCode($dCode)
    {
        switch ($dCode) {
            case '€':
                return 'eur';
                break;
            default:
                return strtolower($dCode);
                break;
        }
    }
}

/**
 * Add Basket to Stripe Item List
 */
$aLineItems = [];
foreach ($oAPIResponse->positions as $oItem) {
    if(is_object($oItem)) {
        $sLabel = $oItem->oArticle->label;
        $sDesc = (isset($oItem->oVariant)) ? $oItem->oVariant->label : '-';
        $dPrice = (int)$oItem->oVariant->price * 100;
        $sImg = $oItem->oArticle->featured_image;
        if($oItem->ref_type == 'event') {
            $sLabel = $oItem->oEvent->label;
            $sDesc = $oItem->oArticle->label;
            $sImg = $oItem->oEvent->featured_image;
            $dPrice = (int)$oItem->price * 100;
        }

        $aLineItems[] = [
            'name' => $sLabel,
            'description' => $sDesc,
            'images' => [$sHost.$sImg],
            'amount' => $dPrice,
            'currency' => getStripeCurrencyCode(get_option('plcshop_currency_main')),
            'quantity' => (int)$oItem->amount,
        ];
    }
}

/**
 * Create Stripe Session
 */
$session = \Stripe\Checkout\Session::create([
    'customer_email' => $oAPIResponse->basket->contact->email_private,
    'payment_method_types' => ['card'],
    'line_items' => $aLineItems,
    'success_url' => $sWPUrl.'/'.$sBasketSlug.'/paymentsuccess?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $sWPUrl.'/'.$sBasketSlug.'/cancel',
]);

$oAPIResponse = \OnePlace\Connect\Plugin::getDataFromAPI('/basket/wordpress/stripe', [
    'shop_session_id'=>session_id(),
    'session_id' => $session->id,
    'payment_id' => $session->payment_intent,
]);

if($oAPIResponse->state == 'success') {
    ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('<?=$sPubKey?>\n');
        stripe.redirectToCheckout({
            sessionId: '<?=$session->id?>'
        }).then(function (result) {
        });
    </script>
    <?php
} else { ?>
    <div class="elementor-widget-container">
        <div class="elementor-alert elementor-alert-danger" role="alert">
            <span class="elementor-alert-title">Could not start stripe payment</span>
            <span class="elementor-alert-description">Wordpress Shop Session ID: <b><?=session_id();?></b></span>
            <button type="button" class="elementor-alert-dismiss">
                <span aria-hidden="true">×</span>
                <span class="elementor-screen-only">Warnung verwerfen</span>
            </button>
        </div>
    </div>
<?php } ?>