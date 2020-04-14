<div>
    <div class="panel">
        <div class="panel-heading">
            <span>{$panel_title}</span>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <div class="well">
                    <h3><i class="icon-map-marker"></i> {l s='Address' mod='ezmultistore'}</h3>
                    <p>
                        <img class="img-thumbnail" src="{$store_image['large']['url']}"/>
                    </p>
                    <p>
                        <b>{$store->name[$id_lang]}</b><br>
                        {$store->address1[$id_lang]}<br>
                        {$store->address2[$id_lang]}
                    </p>
                    <p>
                        {$store->postcode} {$store->city}<br>
                        {$country->name[$id_lang]} {$state->name[$id_lang]}
                    </p>
                </div>
            </div>
            <div class="col-xs-4">
                <dl class="well list-detail">
                    <h3><i class="icon-info"></i> Informations</h3>
                    <dt>E-mail</dt>
                    <dd><a href="mailto:{$store->email}"><i class="icon-envelope-o"></i> {$store->email}</a></dd>

                    <dt>Phone contact</dt>
                    <dd><a href="tel:{$store->phone}"><i class="icon-phone"></i> {$store->phone}</a></dd>

                    <dt>{l s='VAT Number' mod='ezmultistore'}</dt>
                    <dd>123456789</dd>

                    <dt>{l s='SIRET Number' mod='ezmultistore'}</dt>
                    <dd>123456789</dd>
                </dl>
            </div>
            <div class="col-xs-4">
                <dl class="well list-detail">
                    <h3><i class="icon-calendar"></i> {l s='opening hours' mod='ezmultistore'}</h3>
                    <dt>{l s='Monday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Tuesday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Wednesday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Thursday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Friday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Saturday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                    <dt>{l s='Sunday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0]}</a></dd>
                </dl>
            </div>
        </div>

    </div>
</div>
