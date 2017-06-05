/**
 * Created by Ray on 17/6/2.
 */
define(function () {
    var util = {};

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
                    tip('登录验证已经过期，请重新登录!~', 'error');

                    setTimeout(function() {
                        window.location.href = '/new/login';
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

    return util;
});