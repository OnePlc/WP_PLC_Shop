<article class="plc-admin-page-general plc-admin-page">
    <h1><?=__('General Settings','wpplc-swissknife')?></h1>
    <p>Here you find the core settings for the shop</p>

    <?php if(is_plugin_active('elementor/elementor.php')) { ?>
    <h3>Elementor Integration</h3>
    <!-- Elementor Integration -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bElementorActive = get_option( 'plcshop_elementor_active', false ); ?>
            <input name="plcshop_elementor_active" type="checkbox" <?=($bElementorActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Enable Elementor Integration','wp-plc-shop')?></span>
    </div>
    <!-- Elementor Integration -->
    <?php } ?>

    <h3>Region Settings</h3>
    <!-- Shop Currency -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_currency_main"  class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select Currency' ) ); ?></option>
            <?php
            $aShopCurrencies = ['$'=>'USD','CHF'=>'CHF','€'=>'Euro'];
            $sCurrentCurrency = get_option('plcshop_currency_main');
            foreach ( $aShopCurrencies as $location => $description ) {
                $sChecked = ($location == $sCurrentCurrency) ? ' selected="selected"' : '';
                echo '<option value="'.$location.'"'.$sChecked.'>';
                echo $location . ': ' . $description . '</option>';
            }
            ?>
        </select>
        <span>Shop Currency</span>
    </div>
    <!-- Shop Currency -->

    <!-- Currency Position -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_currency_pos"  class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select Position' ) ); ?></option>
            <?php
            $aShopCurrencies = ['before'=>'Before','after'=>'After'];
            $sCurrentCurrency = get_option('plcshop_currency_pos');
            foreach ( $aShopCurrencies as $location => $description ) {
                $sChecked = ($location == $sCurrentCurrency) ? ' selected="selected"' : '';
                echo '<option value="'.$location.'"'.$sChecked.'>';
                echo $location . ': ' . $description . '</option>';
            }
            ?>
        </select>
        <span>Currency Position</span>
    </div>
    <!-- Currency Position -->

    <h3>Shop Pages</h3>
    <!-- Shop Main Page -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_pages_main" class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
            <?php
            $selected_page = get_option( 'plcshop_pages_main' );
            $pages = get_pages();
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ';
                $option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
                $option .= '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            ?>
        </select>
        <span>Shop Main Page</span>
    </div>
    <!-- Shop Main Page -->

    <!-- Shop Basket Page -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_pages_basket" class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
            <?php
            $selected_page = get_option( 'plcshop_pages_basket' );
            $pages = get_pages();
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID . '" ';
                $option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
                $option .= '>';
                $option .= $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            ?>
        </select>
        <span>Shop Basket Page</span>
    </div>
    <!-- Shop Basket Page -->

    <h3>Plugin Info</h3>
    <?php if(is_plugin_active('wp-plc-events/wp-plc-events.php')) { ?>
        <p style="color:green;">Event Plugin loaded</p>
    <?php } ?>

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-general">Save General Settings</button>
    <!-- Save Button -->
</article>