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

        $('#site-container').mixItUp({
            load: {
                sort: 'title:asc'
            }
        });

        var mixHeight = 0;
        $('.mix').each(function(){
            if($(this).height() > mixHeight) {
                mixHeight = $(this).height();
            }
        });
        $('.mix').height(mixHeight - 10);
    }

    return {
        'init'	: init
    };
}());

APP.rath3rApi.init();