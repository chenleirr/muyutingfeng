define(function (require) {
    var util = require('../common/util');
    var vex = util.vex;

    $('#main_title').html('登录');

    $('#login').click(function () {
        var data = $("#login_form").serializeArray();
        var params = {};

        $.each(data, function(i, item){
            params[item.name] = item.value;
        });

        var obj = {
            url : '/api/login',
            type : 'POST',
            params : params
        };

        util.ajax(obj, function (data) {
            var code = data.code;

            if (code === 0) {
                vex.dialog.confirm({
                    message : '登录成功!',
                    callback : function () {
                        window.location.href = '/home';
                    }
                });

            } else {
                vex.dialog.alert(data.msg || '服务错误!');
            }
        });
    });
});