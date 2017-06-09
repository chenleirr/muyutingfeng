define(function (require) {
    var util = require('../common/util');
    var showdown = require('https://cdn.rawgit.com/showdownjs/showdown/1.7.1/dist/showdown.min');
    var converter = new showdown.Converter();
    var obj = {
        url : '/api/article/get_by_id',
        type : 'GET',
        params : {
            id : 6
        }
    };

    function init() {
        util.ajax(obj, function (data) {
            var content = data.info.content;

            var html = converter.makeHtml(content);

            $('#main_content').html(html);
        });
    }

    init();
});
