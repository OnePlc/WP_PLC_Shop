<article class="plc-admin-page-gateways plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('Payment Gateways','wp-plc-shop')?></h1>
    <p>Here you find all the settings related to payment gateways</p>

    <h3>Stripe</h3>

    <!-- Enable Stripe TEST Mode -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bEnableTest = get_option( 'plcshop_gateway_stripe_enable_test', false ); ?>
            <input name="plcshop_gateway_stripe_enable_test" type="checkbox" <?=($bEnableTest == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Stripe TEST Mode</span>
    </div>
    <!--Enable Stripe TEST Mode -->

    <!-- Stripe Public Key (TEST) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_stripe_pk_test'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_gateway_stripe_pk_test" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Stripe Public Key (TEST)</span>
    </div>
    <!-- Stripe Public Key (TEST) -->

    <!-- Stripe API Secret (TEST) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_stripe_sk_test'); ?>
        <input type="password" class="plc-settings-value" name="plcshop_gateway_stripe_sk_test" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Stripe API Secret (TEST)</span>
    </div>
    <!-- Stripe API Secret (TEST) -->

    <!-- Stripe Public Key (LIVE) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_stripe_pk_live'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_gateway_stripe_pk_live" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Stripe Public Key (LIVE)</span>
    </div>
    <!-- Stripe Public Key (LIVE) -->

    <!-- Stripe API Secret (LIVE) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_stripe_sk_live'); ?>
        <input type="password" class="plc-settings-value" name="plcshop_gateway_stripe_sk_live" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Stripe API Secret (LIVE)</span>
    </div>
    <!-- Stripe API Secret (LIVE) -->

    <h3>Paypal</h3>

    <!-- Enable Paypal TEST Mode -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bEnableTest = get_option( 'plcshop_gateway_paypal_enable_test', false ); ?>
            <input name="plcshop_gateway_paypal_enable_test" type="checkbox" <?=($bEnableTest == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span>Enable Paypal SANDBOX Mode</span>
    </div>
    <!--Enable Paypal TEST Mode -->

    <!-- Paypal Client ID (SANDBOX) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_paypal_clientid_test'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_gateway_paypal_clientid_test" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Paypal Client ID (SANDBOX)</span>
    </div>
    <!-- Paypal Client ID (SANDBOX) -->

    <!-- Paypal Client Secret (SANDBOX) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_paypal_clientsecret_test'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_gateway_paypal_clientsecret_test" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Paypal Client Secret (SANDBOX)</span>
    </div>
    <!-- Paypal Client Secret (SANDBOX) -->

    <!-- Paypal Client ID (LIVE) -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_paypal_clientid_live'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_gateway_paypal_clientid_live" value="<?=$sCurVal?>" style="width:50%; min-width:200px;" />
        <span>Paypal Client ID (LIVE)</span>
    </div>
    <!-- Paypal Client ID (LIVE) -->

    <h3>Prepay</h3>

    <!-- Bank Account Data -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_gateway_prepay_bankacc'); ?>
        <?php
        wp_editor(html_entity_decode($sCurVal),"plcshop_gateway_prepay_bankacc", [
            'textarea_rows'=>12, 'editor_class'=>'plc-settings-value','media_buttons' => false
        ]);
        ?>
        <span>Bank Account Data</span>
    </div>
    <!-- Bank Account Data -->

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-gateways">Save Payment Gateway Settings</button>
    <!-- Save Button -->
</article>