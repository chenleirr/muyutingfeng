define(function (require) {

    var util = require('../common/util');
    var vex = util.vex;
    var showdown = require('/web/showdown/js/showdown.min');
    var converter = new showdown.Converter();
    var show_show = true;
    var hide_val = '隐藏>>';
    var whow_val = '展开<<';

    $('#do_save').click(function () {
        var data = $("#edit_form").serializeArray();
        var params = {};

        $.each(data, function(i, item){
            params[item.name] = item.value;
        });

        var obj = {
            url : '/api/article/insert',
            type : 'POST',
            params : params
        };

        vex.dialog.confirm({
            message : '确定要保存吗?',
            callback : function (value) {
                if (!value) {
                    return;
                }
                util.ajax(obj, function (data) {
                    var code = data.code;

                    if (code === 0) {
                        vex.dialog.confirm({
                            message : '保存成功!',
                            callback : function () {
                                window.location.href = '/home';
                            }
                        });

                    } else {
                        vex.dialog.alert(data.msg || '服务错误!');
                    }
                });
            }
        });

    });

    $('#markdown_area').keyup(function () {
        var content = $('#markdown_area').val();
        var html = converter.makeHtml(content);

        $('#markdown_show').html(html);
    });

    $('#button_hide').click(function () {
        if (show_show) {
            $('#markdown_show').hide();
            $('#edit_container').addClass('edit_container_hidden');
            $('#button_hide').val(whow_val);
            show_show = false;
        } else {
            $('#markdown_show').show();
            $('#edit_container').removeClass('edit_container_hidden');
            $('#button_hide').val(hide_val);
            show_show = true;
        }

    });
});