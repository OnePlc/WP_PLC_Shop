<form method="POST" action="/<?=$sBasketSlug?>/payment" class="plc-shop-form">
    <?php if(isset($oAPIResponse->deliverymethods)) { ?>
    <div style="width:100%; display: inline-block; padding:8px;">
        <?php $iDelivery = (!empty($oAPIResponse->basket->deliverymethod_idfs)) ? $oAPIResponse->basket->deliverymethod_idfs : 1; ?>
        Wie möchten Sie Ihre Gutscheine / Tickets erhalten ?<br/>
        <?php foreach($oAPIResponse->deliverymethods as $oDelMethod) { ?>
        <input type="radio" name="address_deliverymethod" value="<?=$oDelMethod->id?>"<?=($iDelivery == $oDelMethod->id) ? ' checked ' : ''?>/> <?=$oDelMethod->label?>
        <?php } ?>
    </div>
    <?php } ?>
    <div style="display: inline-block; width:100%;">
        <div style="width:100%; float:left; display: inline-block;">
            <div class="plc-shop-checkout-address-salution">
                <label for="address_salution">
                    Anrede*
                </label>
                <select name="address_salution" class="plc-shop-input">
                    <option value="1">Herr</option>
                    <option value="2">Frau</option>
                </select>
            </div>
        </div>
        <div class="plc-shop-checkout-formcontrol">
            <label for="address_firstname">
                Vorname*
            </label>
            <?php $sVal = (isset($oContact)) ? $oContact->firstname : ''?>
            <input type="text" name="address_firstname" class="plc-shop-input"  value="<?=$sVal?>" required />
        </div>
        <div class="plc-shop-checkout-formcontrol">
            <label for="address_lastname">
                Nachname*
            </label>
            <?php $sVal = (isset($oContact)) ? $oContact->lastname : ''?>
            <input type="text" name="address_lastname" class="plc-shop-input"  value="<?=$sVal?>" required />
        </div>
        <div style="width:100%; float:left; display: inline-block;"  class="plc-shop-checkout-only-mail">
            <div class="plc-shop-checkout-formcontrol">
                <label for="address_street">
                    Strasse, Nr
                </label>
                <?php $sVal = (isset($oContact->address)) ? $oContact->address->street : ''?>
                <input type="text" name="address_street" class="plc-shop-input"  value="<?=$sVal?>" />
            </div>
            <div class="plc-shop-checkout-formcontrol">
                <label for="address_zip" style="float:left; width:100%;">
                    PLZ, Ort
                </label>
                <?php $sVal = (isset($oContact->address)) ? $oContact->address->zip : ''?>
                <input type="text" name="address_zip" class="plc-shop-input plc-shop-input-zip" style="background:#edebeb; float:left;" size="5" value="<?=$sVal?>" />
                <?php $sVal = (isset($oContact->address)) ? $oContact->address->city : ''?>
                <input type="text" name="address_city" class="plc-shop-input plc-shop-input-city" style="background:#edebeb; float:left;" value="<?=$sVal?>" />
            </div>
        </div>
        <div class="plc-shop-checkout-formcontrol">
            <label for="address_email">
                E-Mail Adresse*
            </label>
            <?php $sVal = (isset($oContact)) ? $oContact->email_private : ''?>
            <input type="email" name="address_email" class="plc-shop-input"  value="<?=$sVal?>" />
        </div>
        <div class="plc-shop-checkout-formcontrol">
            <label for="address_phone" style="width:100%;">
                Tel für Rückfragen
            </label><br/>
            <?php $sVal = (isset($oContact)) ? $oContact->phone : ''?>
            <input id="address_phone" type="tel" name="address_phone" class="plc-shop-input"  value="<?=$sVal?>" />
            <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.7/build/js/intlTelInput.min.js"></script>
            <script>
                var input = document.querySelector("#address_phone");
                var telP = window.intlTelInput(input, {
                    separateDialCode:true,
                    preferredCountries: ["de","ch","at"],
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@16.0.7/build/js/utils.js'
                });

                telP.setCountry("de");
            </script>
        </div>
        <div style="width:100%; float:left; padding:8px;">
            <label for="address_comment">
                Anmerkungen
            </label>
            <?php $sVal = (isset($oAPIResponse->basket)) ? $oAPIResponse->basket->comment : ''?>
            <textarea class="plc-shop-input"  name="address_comment"><?=$sVal?></textarea>
        </div>

        <div style="width:100%; float:left; padding:8px;">
            <button class="plc-shop-checkout-button" type="submit" style="border:0;">
                <?php if(!empty($aSettings['btn_address_selected_icon'])) { ?>
                    <i class="<?=$aSettings['btn_address_selected_icon']['value']?>" style="width:20px;"></i>
                <?php } ?>
                <?=$aSettings['btn_address_text']?>
            </button>
        </div>
    </div>
</form>
