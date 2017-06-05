define(function (require) {
    var util = require('../common/util');
    var obj = {
        url : '/api/get_data',
        type : 'GET',
        params : {}
    };

    function init() {
        util.ajax(obj, function (data) {
            $('#name').val(data.name || 'none');
        });
    }

    init();
});
