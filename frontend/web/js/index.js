$(function () {
    initCssValue();

    loginSendDynamicKey();
    login();

    resetPasswordSendDynamicKey();
    resetPassword();

    changePassword();

    changeEmailSendDynamicKey();
    changeEmail();

    signUp();

    sendEmail();

    $('.datetimepicker').datetimepicker({
        format: 'Y-m-d H:i',
        maxDate: 'tomorrow'
    });

    createEvent();
    addLocation();
});


function addLocation() {
    $('#add-location').click(function () {
        $('.form input:text').each(function () {
            if ($('.form input:radio:checked').val() != 'people' && $(this).hasClass('phone')) {
                return true;
            }
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });
        var data = {};
        data.event_id = $.trim($('#event_id').val());
        data.city = $.trim($('#location-city').val());
        data.title_from_provider = $.trim($('#location-title').val());
        data.occur_at = $.trim($('#occur_at').val());
        data.provided_at = $.trim($('#provided_at').val());
        data.identity_kind = $('.form input:radio:checked').val();
        data.identity_info = $.trim($('#phone').val());

        $.ajax({
            url: '/find/event/add-location',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/find/event/event/' + data.event_id;
                } else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#add-location-result h5').html(message);
                }
            }
        });
    });
}

function createEvent() {
    $('#create-event').click(function () {
        $('.form input:text').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });
        var data = {};
        data.theme = $.trim($('#theme').val());
        data.description = $.trim($('#description').val());
        data.city = $.trim($('#city').val());
        data.title_from_provider = $.trim($('#title-from-provider').val());
        data.urgent = $('.form input:radio:checked').val()
        data.occur_at = $.trim($('#datetimepicker').val());

        $.ajax({
            url: '/find/event/create-event',
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
                    $('#create-event-result h5').html(message);
                }
            }
        });
    });
}

function sendEmail() {
    $('#send-email').click(function () {

        $('.form .contact .input-class').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var data = {};
        data.email = $.trim($('#email').val());
        data.subject = $.trim($('#subject').val());
        data.body = $.trim($('textarea').val());

        $.ajax({
            url: '/user/index/contact',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/user/index/profile';
                } else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#send-email-result h5').html(message);
                }
            }
        });
    });
}

function signUp() {
    $('#sign-up').click(function () {

        $('.form input').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var data = {};
        data.username = $.trim($('#name').val());
        data.email = $.trim($('#email').val());
        data.password = $.trim($('#password').val());
        $.ajax({
            url: '/user/index/sign-up',
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
                    $('#sign-up-result h5').html(message);
                }
            }
        });
    });

}

function changeEmail() {
    $('#change-email').click(function () {

        $('.form input').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var data = {};
        data.new_email = $.trim($('#email').val());
        data.dynamic_key = $.trim($('#dynamic-key').val());
        $.ajax({
            url: '/user/index/change-email',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/user/index/index';
                } else {
                    $('#change-email-result h5').html(res.message);
                }
            }
        });
    });
}

function changeEmailSendDynamicKey() {
    $('#send-dynamic-key').click(function () {

        var new_email = $.trim($('#email').val());
        if (!new_email) {
            $('#change-email-result h5').html('输入新邮箱后才可以发送动态口令');
            return;
        }

        var data = {};
        data.new_email = new_email;
        $.ajax({
            url: '/user/index/verify-old-email',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#change-email-result h5').html('动态口令发送成功,10分钟后过期,请及时查收邮件');
                }
                else {
                    $('#change-email-result h5').html(res.message);
                }
            }
        });
    });
}

function changePassword() {
    $('#change-password').click(function () {

        $('.form input').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var data = {};
        data.old_password = $.trim($('#old-password').val());
        data.new_password = $.trim($('#new-password').val());
        data.new_password_confirm = $.trim($('#password-confirm').val());
        $.ajax({
            url: '/user/index/change-password',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/user/index/login';
                } else {
                    $('#change-password-result h5').html(res.message);
                }
            }
        });
    });

}

function resetPassword() {
    $('#reset').click(function () {

        $('.form input').each(function () {
            if (!$.trim($(this).val())) {
                $(this).css({'border': '1px dotted red'});
                return false;
            } else {
                $(this).css({'border': 'none'});
            }
        });

        var data = {};
        data.username = $.trim($('#name').val());
        data.email = $.trim($('#email').val());
        data.password = $.trim($('#password').val());
        data.password_confirm = $.trim($('#password-confirm').val());
        data.dynamic_key = $.trim($('#dynamic-key').val());
        $.ajax({
            url: '/user/index/reset-password',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    location.href = '/user/index/login';
                } else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#reset-result h5').html(message);
                }
            }
        });
    });

}

function resetPasswordSendDynamicKey() {
    $('#send-dynamic-key').click(function () {

        var name = $.trim($('#name').val());
        var email = $.trim($('#email').val());
        if (!name || !email) {
            $('#reset-result h5').html('输入用户名和邮箱后才可以发送动态口令');
            return;
        }

        var data = {};
        data.username = name;
        data.email = email;
        $.ajax({
            url: '/user/index/send-dynamic-key',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#reset-result h5').html('动态口令发送成功,10分钟后过期,请及时查收邮件');
                }
                else {
                    var message = '';
                    for (var key in res.message) {
                        message += res.message[key];
                        break;
                    }
                    $('#reset-result h5').html(message);
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

        var data = {};
        data.username = $.trim($('#name').val());
        data.password = $.trim($('#password').val());
        data.dynamic_key = $.trim($('#dynamic-key').val());
        data.remamberMe = $('.form input')[3].checked;
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

function loginSendDynamicKey() {
    $('#send-dynamic-key').click(function () {

        var name = $.trim($('#name').val());
        var password = $.trim($('#password').val());
        if (!name || !password) {
            $('#login-result h5').html('输入用户名和密码后才可以发送动态口令');
            return;
        }

        var data = {};
        data.username = name;
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
