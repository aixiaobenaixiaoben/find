$(function () {

    initBoardLeftPadding();
    initContactPadding();

    initHoverColor();
    initColorOfTagIcon();

    listenMenuIconClick();
    listenSubmitMessage();

});

/**
 * listen and send the message the visitor submits to server
 */
function listenSubmitMessage() {

    var contact = $('.form .contact');

    var preg = /^\w+(\.\w+)*@\w+(\.\w+)+$/;
    $('.form .contact .send-button').click(function () {
        var name = $.trim(contact.find('#guest-name').val());
        var email = $.trim(contact.find('#guest-email').val());
        var title = $.trim(contact.find('#guest-title').val());
        var content = $.trim(contact.find('#guest-content').val());

        if (name == '') {
            alert('是不是该留一下您的芳名呢');
            return false;
        }
        if (email == '') {
            alert('填一下邮箱呗,酱紫我才能联系到你啊');
            return false;
        }
        if (!preg.test(email)) {
            alert('邮箱格式好像不太对哦');
            return false;
        }
        if (title == '') {
            alert('关于什么主题呢');
            return false;
        }
        if (content == '') {
            alert('想说些什么就写下来吧^o^');
            return false;
        }

        var data = {};
        data.name = name;
        data.email = email;
        data.title = title;
        data.content = content;
        $.ajax({
            url: '/index/message',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert('感谢你的留言,我会尽快通过邮件和你取得联系');
                    location.href = 'http://freedom.forfreedomandlove.com';
                } else {
                    alert('sorry,留言没有成功记录下来,可能发生了什么不对的事情,要不再试一次？^o^');
                }
            },
            error: function (res) {
                alert('sorry,留言没有成功记录下来,可能发生了什么不对的事情,要不再试一次？^o^');
            }
        });
    });
}

/**
 * load the page of contact
 */
function initContactPadding() {
    if (window.innerWidth > 1024) {
        $('.form .contact').addClass('petty');
    }
}

/**
 * init the left part of the page
 * collaborate with the size of foundation framework
 */
function initBoardLeftPadding() {
    if (window.innerWidth < 1024) {
        $('.board').css({'padding-left': '10px'});
        $('.view').css({'padding-left': '10px'});
        $('.board .item .about').css({'height': '100px'});
    } else {
        $('.board').css({'padding-bottom': '120px'});
        $('.view').css({'padding-bottom': '120px'});
    }

    if (window.innerWidth < 640) {
        $('header h1').css({'text-align': 'center'});
    } else if (window.innerWidth < 1024 && window.innerWidth >= 640) {
        $('header h1').css({'padding-left': '100px'});
    }
}

/**
 * init the color of tag icon
 */
function initColorOfTagIcon() {
    $('.side .tag .label:odd').removeClass('teal').addClass('purple');
}

function listenMenuIconClick() {
    $('.program .side .list .parent i').click(function () {
        $(this).parents('.parent').next().slideToggle(300);
    });
}

/**
 * listen and change the color of part hovered gradually
 */
function initHoverColor() {
    $('header .nav h4').hover(
        function () {
            $(this).animate({
                'color': '#FDFDFD',
                'background-color': '#3BAF9B'
            }, 'fast');
        },
        function () {
            $(this).animate({
                'color': '#575064',
                'background-color': '#FDFDFD'
            });
        }
    );

    $('.program .side .list .parent').hover(
        function () {
            $(this).animate({
                'background-color': '#31AB96'
            }, 'fast');
        },
        function () {
            $(this).animate({
                'background-color': '#494158'
            }, 'fast');
        }
    );

    $('.program .side .list .parent i').hover(
        function () {
            $(this).animate({
                'color': '#FDFDFD'
            }, 'fast');
        },
        function () {
            $(this).animate({
                'color': '#A9ACAD'
            });
        }
    );

    $('.program .side .list .sub-list .child').hover(
        function () {
            $(this).animate({
                'background-color': '#CCCCCC'
            }, 'fast');
        },
        function () {
            $(this).animate({
                'background-color': '#F3F3F3'
            }, 'fast');
        }
    );
}