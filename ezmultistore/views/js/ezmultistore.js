$(document).ready(function () {

    const p = (x) => console.log(x);

    const ezBoxShow = (a = 500) => {
        $('#test_box').show(a);
    }
    const ezBoxHide = (a = 500) => {
        $('#test_box').hide(a);
    }

    let carrierId = $('#ez_multistore_id').val();

    if ( $('#delivery_option_' + carrierId).is(':checked')) ezBoxShow();

    $('[id^="delivery_option_"]').click(function() {

        if ($('#delivery_option_' + carrierId).is(':checked')) {

            ezBoxShow();

        } else ezBoxHide();
    });


});