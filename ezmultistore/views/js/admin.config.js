$(document).ready(function(){

    $('.menu_tab').click(function()
    {
        $('.menu_tab').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
    });

});