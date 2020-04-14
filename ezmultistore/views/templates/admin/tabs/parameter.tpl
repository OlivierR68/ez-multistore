<h3><i class="icon-cogs"></i> {l s='store access authorization' mod='firstmodule'}</h3>
<form role="form" action="#" method="POST" id="submitParameter" name="test_form">

    <div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="FIRSTNAME">
                        <span class="label-tooltip" data-toggle="tooltip" title="Enter your firstname here">
                            {l s='Firstname' mod='firstmodule'}
                        </span>
                    </label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <input type="text" name="FIRSTNAME" id="FIRSTNAME">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-xs-6">

            </div>
        </div>
    </div>


    <div class="clearfix"></div>

    {* Footer avec les actions *}
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <button name="submitParameters" id="submitParameters" type="submit" class="btn btn-default">
                <i class="process-icon-save"></i>
                {l s='Save' mod='firstmodule'}
            </button>
        </div>
    </div>


</form>