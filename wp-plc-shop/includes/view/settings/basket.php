<article class="plc-admin-page-basket plc-admin-page" style="padding: 10px 40px 40px 40px;">
    <h1><?=__('Basket Settings','wp-plc-shop')?></h1>
    <p>Here you find all the settings related to shop basket</p>

    <!-- Basket Debug Mode -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bDebugActive = get_option( 'plcshop_elementor_basket_debugmode', false ); ?>
            <input name="plcshop_elementor_basket_debugmode" type="checkbox" <?=($bDebugActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Basket Debug Mode','wp-plc-shop')?></span>
    </div>
    <!-- Basket Debug Mode -->

    <!-- Basket Maintenance Mode -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bDebugActive = get_option( 'plcshop_basket_maintenancemode', false ); ?>
            <input name="plcshop_basket_maintenancemode" type="checkbox" <?=($bDebugActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Maintenance Mode','wp-plc-shop')?></span>
    </div>
    <!-- Basket Maintenance Mode -->

    <h3>Basket Slug</h3>
    <!-- Basket Slug -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_basket_slug'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_basket_slug" value="<?=$sCurVal?>" style="width:20%; min-width:50px; max-width:100px;" />
        <span>Basket Slug</span>
    </div>
    <!-- Basket Slug -->

    <h3>Basket Menu Icon</h3>
    <!-- Basket Icon Menu -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_basket_icon_menu"  class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select Menu' ) ); ?></option>
            <?php
            $menus = get_registered_nav_menus();
            $sCurrentMenu = get_option('plcshop_basket_icon_menu');
            foreach ( $menus as $location => $description ) {
                $sChecked = ($location == $sCurrentMenu) ? ' selected="selected"' : '';
                echo '<option value="'.$location.'"'.$sChecked.'>';
                echo $location . ': ' . $description . '</option>';
            }
            ?>
        </select>
        <span>Select Menu to Attach Basket Icon</span>
    </div>
    <!-- Basket Icon Menu -->

    <h3>Basket Step Indicator</h3>

    <!-- Basket Step Indicator -->
    <div class="plc-admin-settings-field">
        <select name="plcshop_basket_steps_style"  class="plc-settings-value">
            <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select Style' ) ); ?></option>
            <?php
            $aStepStyles = ['dots'=>'Dots','msicons'=>'Multistep Icons','triangleicons'=>'Triangle Icons','triangle'=>'Triangle'];
            $sCurrentMenu = get_option('plcshop_basket_steps_style');
            foreach ( $aStepStyles as $location => $description ) {
                $sChecked = ($location == $sCurrentMenu) ? ' selected="selected"' : '';
                echo '<option value="'.$location.'"'.$sChecked.'>';
                echo $location . ': ' . $description . '</option>';
            }
            ?>
        </select>
        <span>Select Basket Step Indicator Style</span>
    </div>
    <!-- Basket Step Indicator -->

    <!-- Basket Steps Color -->
    <div class="plc-admin-settings-field">
        <?php $sCurVal = get_option('plcshop_basket_steps_color'); ?>
        <input type="text" class="plc-settings-value" name="plcshop_basket_steps_color" value="<?=$sCurVal?>" style="width:20%; min-width:50px; max-width:100px;" />
        <span>Basket Steps Color</span>
    </div>
    <!-- Basket Steps Color -->

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-basket">Save Basket Settings</button>
    <!-- Save Button -->
</article>