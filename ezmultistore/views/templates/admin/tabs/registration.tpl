

<h3><i class="icon-legal"></i> {l s='Stores registration numbers' mod='ezmultistore'}</h3>
<form role="form" action="#" method="POST" id="submitRegistration">
    
    <div class="alert alert-dismissible alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p>
            {l s='You can add optionnal store\'s informations, they will be displayed in the invoices, ex: VAT Number, SIRET number, ect...' mod='ezmultistore'}
        </p>
    </div>


    <div>
        {foreach from=$stores key=$key item=$store}
            <div class="form-group row">
                <label for="REGISTRATIONS_STORE_{$store['id']}" class="col-sm-2 col-form-label">{$store['name']}<br><span style="font-weight: normal">{$store['postcode']} {$store['city']}</span></label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="REGISTRATIONS_STORE_{$store['id']}" rows="3"
                              {if $key eq 0}placeholder="{l s='VAT Number' mod='ezmultistore'}: FR 123456789&#10;{l s='SIRET Number' mod='ezmultistore'}: 362 521 879 00034"{/if}
                    ></textarea>
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
            <button name="submitRegistration" id="submitRegistration_button" type="submit" class="btn btn-default">
                <i class="process-icon-save"></i>
                {l s='Save' mod='ezmultistore'}
            </button>
        </div>
    </div>


</form>