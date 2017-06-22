/**
 * Created by Ray on 17/6/2.
 */
define(function (require) {
    var util = {};
    var vex = require('/web/vex/js/vex.combined');

    vex.defaultOptions.className = 'vex-theme-default';
    vex.dialog.buttons.YES.text = '好的';
    vex.dialog.buttons.NO.text = '取消';

    $.ajaxPrefilter(function(options, originalOptions, jqXhr) {
        jqXhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        options.cache = false;
    });

    util.ajax = function (obj, callback) {
        $.ajax({
            url: obj.url,
            type: obj.type || 'GET',
            data: obj.params,
            dataType: "json",

            success: function(result) {
                callback(result || {});
            },
            error: function(result) {
                if(obj.loading) {
                    $('#loadingBox').hide();
                }
                if (result && result.status === 401) {
                    vex.dialog.alert('获取失败');

                    setTimeout(function() {
                        window.location.href = '/home';
                    }, 1500);
                    return;
                }
            },
            complete: function() {
                if(obj.loading) {
                    $('#loadingBox').hide();
                }
                obj.complete && obj.complete();
            }

        });
    };

    //获取url中的参数
    util.getURLParameter = function (name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)')
                .exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
    };

    //vex
    util.vex = vex;

    return util;
});