var APP = APP || {};

APP.rath3rApi = (function () {

    function init() {

        $( ".date" ).datepicker();

    }

    return {
        'init'	: init
    };
}());

APP.rath3rApi.init();