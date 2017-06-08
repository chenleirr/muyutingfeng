define(function (require) {
    var util = require('../common/util');
    var obj = {
        url : '/api/article/get_by_id',
        type : 'GET',
        params : {
            id : 7
        }
    };

    function init() {
        util.ajax(obj, function (data) {
            console.log(111111111);
            console.log(data);
            $('#main_content').html(data.info.content || 'none');
        });
    }

    init();
});
