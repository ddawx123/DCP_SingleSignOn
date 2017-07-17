var geetest_enabled = 1;

$.ajax({
    url: './geetest/StartCaptchaServlet.php?t=' + (new Date()).getTime(),
    type: "get",
    dataType: "json",
    success: function (data) {
        // 调用 initGeetest 进行初始化
        // 参数1：配置参数
        // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它调用相应的接口
        initGeetest({
            // 以下 4 个配置参数为必须，不能缺少
            gt: data.gt,
            challenge: data.challenge,
            offline: !data.success, // 表示用户后台检测极验服务器是否宕机
            new_captcha: data.new_captcha, // 用于宕机时表示是新验证码的宕机
            timeout: '5000',
            product: "bind", // 产品形式，包括：float，popup
            width: "300px"
            // 更多前端配置参数说明请参见：http://docs.geetest.com/install/client/web-front/
        }, handler);
    },
    error: function () {
        geetest_enabled = 0;
        alert("抱歉，智能验证码后端通讯失败。这可能是因为您的网络不稳定或浏览器不支持HTML5导致的，本次会话验证将自动转为传统图形验证码！");
    }
});

var handler = function (captchaObj) {
    captchaObj.onReady(function () {
        $("#wait").hide();
    }).onSuccess(function () {
        var result = captchaObj.getValidate();
        if (!result) {
            return alert('请完成验证');
        }
        $.ajax({
            url: "./geetest/VerifyLoginServlet.php?t=" + (new Date()).getTime(), // 加随机数防止缓存
            type: 'POST',
            dataType: 'json',
            data: {
                newuser: $('#newuser').val(),
                newpwd: $('#newpwd').val(),
                email: $('#email').val(),
                imgcode: $('#imgcode').val(),
                geetest_challenge: result.geetest_challenge,
                geetest_validate: result.geetest_validate,
                geetest_seccode: result.geetest_seccode
            },
            success: function (data) {
                if (data.status === 'success') {
                    //alert('验证通过，稍后将提交注册请求。');
                    var regform = document.getElementById("form");
                    regform.submit();
                } else if (data.status === 'fail') {
                    alert('抱歉，注册失败了！请完成智能人机验证，以便我们确认您的合法访问。');
                }
            },
            error: function () {
                alert("抱歉，智能验证请求失败！请刷新页面后再次尝试。");
            }
        });
    });
    $('#btn').click(function () {
        if (geetest_enabled === 0) {
            var regform = document.getElementById("form");
            regform.submit();
        }
        else {
            captchaObj.verify();
        }
    });
    // 更多前端接口说明请参见：http://docs.geetest.com/install/client/web-front/
};
    