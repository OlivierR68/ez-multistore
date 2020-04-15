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
                    <dt>{l s='E-mail' mod='ezmultistore'}</dt>
                    <dd><a href="mailto:{$store->email}"><i class="icon-envelope-o"></i> {$store->email}</a></dd>

                    <dt>{l s='Phone contact' mod='ezmultistore'}</dt>
                    <dd><a href="tel:{$store->phone}"><i class="icon-phone"></i> {$store->phone}</a></dd>

                    <dt>{l s='Additionnal informations' mod='ezmultistore'}</dt>
                    <dd>{$store_info}</dd>
                </dl>
            </div>
            <div class="col-xs-4">
                <dl class="well list-detail">
                    <h3><i class="icon-calendar"></i> {l s='opening hours' mod='ezmultistore'}</h3>
                    <dt>{l s='Monday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][0][0]}</a></dd>
                    <dt>{l s='Tuesday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][1][0]}</a></dd>
                    <dt>{l s='Wednesday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][2][0]}</a></dd>
                    <dt>{l s='Thursday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][3][0]}</a></dd>
                    <dt>{l s='Friday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][4][0]}</a></dd>
                    <dt>{l s='Saturday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][5][0]}</a></dd>
                    <dt>{l s='Sunday' mod='ezmultistore'}</dt>
                    <dd>{$store->hours[$id_lang][6][0]}</a></dd>
                </dl>
            </div>
        </div>

        <div class="panel-footer">
            <div class="btn-group pull-left">
                <a href="{$pdf_link}" class="btn btn-default">
                    <i class="process-icon-preview"></i>
                    {l s='Save' mod='ezmultistore'}
                </a>
            </div>
        </div>

    </div>
</div>
