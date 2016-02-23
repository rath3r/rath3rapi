var APP = APP || {};

APP.rath3rApi = (function () {

    function init() {

        $( ".date" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

    }

    return {
        'init'	: init
    };
}());

APP.rath3rApi.init();