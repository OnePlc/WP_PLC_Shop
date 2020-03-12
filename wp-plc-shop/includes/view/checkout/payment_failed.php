<?php
?>
<div class="elementor-widget-container">
    <div class="elementor-alert elementor-alert-danger" role="alert">
        <span class="elementor-alert-title"><b>Could not process payment</b></span>
        <span class="elementor-alert-description">Your payment is most likely already processed. Otherwise there was an error.</b>
        <?php if(is_user_logged_in()) { ?>
            <?=var_dump($oAPIResponse)?>
        <?php } ?></span>
        <button type="button" class="elementor-alert-dismiss">
            <span aria-hidden="true">×</span>
            <span class="elementor-screen-only">Warnung verwerfen</span>
        </button>
    </div>
</div>