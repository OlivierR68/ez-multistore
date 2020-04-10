
<div id="ez_stores" style="display: none" class="py-2">
    <h2 class="step-title h4 mb-1">
        {$ez_title}
    </h2>
    <div class="form-fields">
        <div class="delivery-options">
            {foreach from=$stores key=k item=$store}
                <div class="row delivery-option">
                    <div class="col-sm-1">
                      <span class="custom-radio float-xs-left">
                        <input type="radio" name="EZ_MULTISTORE_DELIVERY_OPTION" id="ez_store_{$store['id_store']}"
                               value="{$store['id_store']}">
                        <span></span>
                      </span>
                    </div>
                    <label for="ez_store_{$store['id_store']}" class="col-sm-11 delivery-option-2">
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <span class="h6 carrier-name">{$store['name']}</span><br>
                                        <img src="{$store['image']['small']['url']}" alt="{$store['image']['legend']}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-xs-12">
                                <span class="carrier-delay">
                                    {$store['address1']}<br>
                                    {$store['address2']}<br>
                                    {$store['postcode']} {$store['city']}<br>
                                </span>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <span class="carrier-price">
                                    <a href="https://www.google.com/maps/search/?api=1&query={$store['latitude']},{$store['longitude']}"
                                       target="_blank">Google Maps</a><br>
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="clearfix"></div>
            {/foreach}
        </div>
    </div>

</div>
