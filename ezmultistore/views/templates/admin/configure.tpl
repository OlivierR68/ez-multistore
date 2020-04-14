<div class="bootstrap">

    <div class="col-lg-2">
        <div class="list-group">
            <a href="#authorizations" class="menu_tab list-group-item active" data-toggle="tab">{l s='Stores Authorizations' mod='ezmultistore'}</a>
            <a href="#registration" class="menu_tab list-group-item" data-toggle="tab">{l s='Registration Numbers' mod='ezmultistore'}</a>
        </div>
        <div class="list-group">
            <a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='ezmultistore'} {$module_version|escape:'htmlall':'UTF-8'}</a>
        </div>
    </div>


    <div class="tab-content col-lg-10">
        <div class="tab-pane panel active" id="authorizations">
            {include file="./tabs/authorizations.tpl"}
        </div>
        <div class="tab-pane panel" id="registration">
            {include file="./tabs/registration.tpl"}
        </div>
    </div>
</div>