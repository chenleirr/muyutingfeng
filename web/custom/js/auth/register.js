define(function (require) {
    var util = require('../common/util');
    var vex = util.vex;
    var countdown = 60;
    var timeout = 0;
    var win = window;

    $('#main_title').html('注册');

    $('#register').click(function () {
        var data = $("#register_form").serializeArray();
        var params = {};

        $.each(data, function(i, item){
            params[item.name] = item.value;
        });

        var obj = {
            url : '/api/register',
            type : 'POST',
            params : params
        };

        util.ajax(obj, function (data) {
            var code = data.code;

            if (code === 0) {
                vex.dialog.confirm({
                    message : '注册成功!',
                    callback : function () {
                        window.location.href = '/home';
                    }
                });

            } else {
                vex.dialog.alert(data.msg || '服务错误!');
            }
        });
    });

    $('#send_sms_code').click(function () {
        var mobile = $('#register_mobile').val();
        var params = {mobile : mobile};
        var obj = {
            url : '/api/send_check_code',
            type : 'POST',
            params : params
        };
        if (!util.test_phone(mobile)) {
            vex.dialog.alert('手机号格式错误!');
            return;
        }
        setCountdown();

        util.ajax(obj, function (data) {
            if (data.code === 0) {
                console.log('发送短息成功。');
            } else {
                vex.dialog.alert(data.msg || '服务错误!');
            }
        });
    });

    //显示倒计时
    function setCountdown(){
        if (countdown === 0) {
            $("#send_sms_code").val("重新获取");
            $("#send_sms_code").removeClass('button_send_disable');
            $("#send_sms_code").removeAttr("disabled");
            win.clearTimeout(timeout);
            countdown = 60;
        } else {
            $("#send_sms_code").val("重新获取 (" + countdown + ")");
            $("#send_sms_code").addClass('button_send_disable');
            $("#send_sms_code").attr('disabled', 'disabled');
            countdown--;
            timeout = win.setTimeout(function() {
                setCountdown();
            }, 1000);
        }
    }
});