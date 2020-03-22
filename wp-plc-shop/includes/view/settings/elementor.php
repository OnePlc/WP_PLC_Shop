<article class="plc-admin-page-elementor plc-admin-page">
    <h1><?=__('Elementor Settings','wp-plc-shop')?></h1>
    <p>Here you find all the settings related to elementor</p>

    <!-- Elementor Version -->
    <div class="plc-admin-settings-field">
        Found Elementor, Version <?=(defined('ELEMENTOR_VERSION')) ? ELEMENTOR_VERSION : '(unknown)'?>
    </div>
    <!-- Elementor Version -->

    <h2><?=__('Elementor Widget','wp-plc-shop')?></h2>

    <!-- Article Slider Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bArtSliderActive = get_option( 'plcshop_elementor_widget_article_slider_active', false ); ?>
            <input name="plcshop_elementor_widget_article_slider_active" type="checkbox" <?=($bArtSliderActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Article Slider Widget','wp-plc-shop')?></span>
    </div>
    <!-- Article Slider Widget -->

    <!-- Basket Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bBasketActive = get_option( 'plcshop_elementor_widget_basket_active', false ); ?>
            <input name="plcshop_elementor_widget_basket_active" type="checkbox" <?=($bBasketActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Basket Widget','wp-plc-shop')?></span>
    </div>
    <!-- Basket Widget -->

    <!-- Featured Box Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bFeatBoxActive = get_option( 'plcshop_elementor_widget_featuredbox_active', false ); ?>
            <input name="plcshop_elementor_widget_featuredbox_active" type="checkbox" <?=($bFeatBoxActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Featured Box Widget','wp-plc-shop')?></span>
    </div>
    <!-- Featured Box Widget -->

    <!-- Showcase Big Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bShowcaseBigActive = get_option( 'plcshop_elementor_widget_showcase_active', false ); ?>
            <input name="plcshop_elementor_widget_showcase_active" type="checkbox" <?=($bShowcaseBigActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('Showcase Big Widget','wp-plc-shop')?></span>
    </div>
    <!-- Showcase Big Widget -->

    <!-- List Widget -->
    <div class="plc-admin-settings-field">
        <label class="plc-settings-switch">
            <?php $bListActive = get_option( 'plcshop_elementor_widget_list_active', false ); ?>
            <input name="plcshop_elementor_widget_list_active" type="checkbox" <?=($bListActive == 1)?'checked':''?> class="plc-settings-value" />
            <span class="plc-settings-slider"></span>
        </label>
        <span><?=__('List Widget','wp-plc-shop')?></span>
    </div>
    <!-- List Widget -->

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-elementor">
        <?=__('Save Elementor Settings','wp-plc-shop')?>
    </button>
    <!-- Save Button -->
</article>