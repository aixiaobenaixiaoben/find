$(function () {
    initCssValue();
    login();
    loginSendDynamicKey();
});

function loginSendDynamicKey() {
    $('#login-send-dynamic-key').click(function () {

        var user_name = $.trim($('#login-user-name').val());
        var password = $.trim($('#login-user-password').val());
        if (!user_name || !password) {
            $('#login-result h5').html('输入用户名和密码后才可以发送动态口令');
            return;
        }

        var data = {};
        data.username = user_name;
        data.password = password;
        $.ajax({
            url: '/user/index/send-dynamic-key',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#login-result h5').html('动态口令发送成功,10分钟后过期,请及时查收邮件');
                }
                else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#login-result h5').html(message);
                }
            }
        });
    });
}

function login() {
    $('#login').click(function () {

        $('.form input').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var user_name = $.trim($('#login-user-name').val());
        var password = $.trim($('#login-user-password').val());
        var dynamic_key = $.trim($('#login-user-key').val());

        var data = {};
        data.username = user_name;
        data.password = password;
        data.dynamic_key = dynamic_key;
        //data.remamberMe = true;
        $.ajax({
            url: '/user/index/login',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/user/index/index';
                } else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#login-result h5').html(message);
                }
            }
        });
    });
}

function initCssValue() {
    if (window.innerWidth < 1024) {
        $('.board').css({'padding-left': '10px'});
        $('.view').css({'padding-left': '10px'});
        $('.board .item .about').css({'height': '100px'});
    } else {
        $('.board').css({'padding-bottom': '150px'});
        $('.view').css({'padding-bottom': '150px'});
        $('.form .contact').addClass('petty');
    }
}
