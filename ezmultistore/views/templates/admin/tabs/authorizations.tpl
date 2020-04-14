

<h3><i class="icon-users"></i> {l s='Stores access authorization' mod='ezmultistore'}</h3>
<form role="form" action="#" method="POST" id="submitAuthorization">

    <div class="alert alert-dismissible alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p>
            {l s='Define here the stores that employees can manage, CTRL+Click to select multiple options.' mod='ezmultistore'}
        </p>
    </div>


    <div>
        {foreach from=$employees key=$key item=$employee}
            <div class="form-group row">
                <label for="storesSelectionsEmployee{$employee['id_employee']}" class="col-sm-2 col-form-label">{$employee['firstname']} {$employee['lastname']}</label>
                <div class="col-sm-10">
                    <select multiple name="EMPLOYEE_{$employee['id_employee']}_STORES[]" class="form-control" id="storesSelectionsEmployee{$employee['id_employee']}">
                        {foreach from=$stores item=$store}
                            <option value="{$store['id_store']}" {if isset($employees_stores[$employee['id_employee']][$store['id_store']])}selected{/if}>
                                {$store['name']} ({$store['postcode']} {$store['city']})
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if $key+1 < (count($employees))}
                <hr>
            {/if}
            <div class="clearfix"></div>
        {/foreach}
    </div>

    <div class="clearfix"></div>

    {* Footer avec les actions *}
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <button name="submitAuthorization" id="submitAuthorization" type="submit" class="btn btn-default">
                <i class="process-icon-save"></i>
                {l s='Save' mod='ezmultistore'}
            </button>
        </div>
    </div>


</form>