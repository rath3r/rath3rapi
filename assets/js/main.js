var APP = APP || {};

APP.rath3rApi = (function () {

    function init() {

        $( ".date" ).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true
        });

        $('#stillUsing').click(function () {
            var x = $(this).val();
            //console.log(x);
            if ($(this).is(':checked')) {
                $(this).val(1);
            }else {
                $(this).val(0);
            }
        });

    }

    return {
        'init'	: init
    };
}());

APP.rath3rApi.init();