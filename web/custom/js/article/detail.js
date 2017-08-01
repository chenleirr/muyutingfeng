define(function (require) {
    var util = require('../common/util');
    var showdown = require('/web/showdown/js/showdown.min');
    var converter = new showdown.Converter();
    var obj = {
        url : '/api/article/get_by_id',
        type : 'GET',
        params : {
            id : util.getURLParameter('id')
        }
    };

    function init() {
        util.ajax(obj, function (data) {
            var info = data.info || {};
            var content = info.content || '';

            if (content !== '') {
                var html = converter.makeHtml(content);

                $('#main_title').html(data.info.title);
                $('#main_content').html(html);
            } else {
                $('#main_content').html('<h4>没有找到文章内容!</h4>');
            }
        });
    }

    init();
});
