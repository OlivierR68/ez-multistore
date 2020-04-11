$(document).ready(function () {

    let storeId = null;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseUrl + 'index.php',
        data: {
            fc: 'module',
            module: 'ezmultistore',
            controller: 'checkout',
            ajax: 1,
            action: 'getUserStore',
            user_id: userId
        }
    }).done(function(response){
        if(response != false) {
            storeId = response;
            $('#ez_store_'+storeId).attr('checked',true);
        } else {
            $('[name="confirmDeliveryOption"]').addClass('disabled');
        }

        ezBoxHide();
        if ( $('#delivery_option_' + carrierId).is(':checked')) ezBoxShow();
    });


    const ezBoxShow = (a = 500) => {
        $('#ez_stores').show(a);
        if (storeId == null) {
            $('[name="confirmDeliveryOption"]').addClass('disabled');
        }
    }

    const ezBoxHide = (a = 500) => {
        $('#ez_stores').hide(a);
        $('[name="confirmDeliveryOption"]').removeClass('disabled');
    }


    $('[id^="delivery_option_"]').click(function() {
        if ($('#delivery_option_' + carrierId).is(':checked')) ezBoxShow();
        else ezBoxHide();
    });

    $('[id^="ez_store_"]').click(function() {

        storeId = $('input[name="EZ_MULTISTORE_DELIVERY_OPTION"]:checked').val();
        if (storeId != null) {
            $('[name="confirmDeliveryOption"]').removeClass('disabled');
        }
    });

    $('[name="confirmDeliveryOption"]').click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseUrl + 'index.php',
            data: {
                fc: 'module',
                module: 'ezmultistore',
                controller: 'checkout',
                ajax: 1,
                action: 'setUserStore',
                user_id: userId,
                store_id: storeId
            }
        })
    });

});