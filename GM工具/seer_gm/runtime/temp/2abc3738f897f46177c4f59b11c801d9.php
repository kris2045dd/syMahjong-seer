<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"E:\PHPTutorial\WWW\fastadmin_revision1\public/../application/admin\view\index\login.html";i:1544776552;}*/ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="renderer" content="webkit">

    <link rel="shortcut icon" href="https://cdn.demo.fastadmin.net/assets/img/favicon.ico" />
    <!-- Loading Bootstrap -->
    <link href="https://cdn.demo.fastadmin.net/assets/css/backend.min.css?v=20181119" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="https://cdn.demo.fastadmin.net/assets/js/html5shiv.js"></script>
    <script src="https://cdn.demo.fastadmin.net/assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        var require = {
            config:  {"site":{"name":"FastAdmin","cdnurl":"https:\/\/cdn.demo.fastadmin.net","version":"20181119","timezone":"Asia\/Shanghai","languages":{"backend":"zh-cn","frontend":"zh-cn"}},"upload":{"cdnurl":"https:\/\/cdn.demo.fastadmin.net","uploadurl":"https:\/\/v0.api.upyun.com\/fastadmin-demo","bucket":"fastadmin-demo","maxsize":"10M","mimetype":"*","multipart":{"policy":"eyJidWNrZXQiOiJmYXN0YWRtaW4tZGVtbyIsInNhdmUta2V5IjoiXC91cGxvYWRzXC97eWVhcn17bW9ufXtkYXl9XC97ZmlsZW1kNX17LnN1ZmZpeH0iLCJleHBpcmF0aW9uIjoxNTQ0NTE1NDQ2LCJub3RpZnktdXJsIjoiaHR0cHM6XC9cL2RlbW8uZmFzdGFkbWluLm5ldFwvYWRkb25zXC91cHl1blwvaW5kZXhcL25vdGlmeSJ9","signature":"a65a3c9af4b1b0f8f4dd9b91e29ef7cf","bucket":"fastadmin-demo","save-key":"\/uploads\/{year}{mon}{day}\/{filemd5}{.suffix}","expiration":1544515446,"notify-url":"https:\/\/demo.fastadmin.net\/addons\/upyun\/index\/notify","ext-param":"0_0"},"multiple":"0"},"modulename":"admin","controllername":"index","actionname":"login","jsname":"backend\/index","moduleurl":"\/admin","language":"zh-cn","fastadmin":{"usercenter":true,"login_captcha":false,"login_failure_retry":false,"login_unique":false,"login_background":"\/assets\/img\/loginbg.jpg","multiplenav":false,"checkupdate":false,"version":"1.0.0.20180806_beta","api_url":"https:\/\/api.fastadmin.net"},"referer":null,"/fastadmin_revision1/public/":"\/","/fastadmin_revision1":"\/","/fastadmin_revision1/public":"https:\/\/cdn.demo.fastadmin.net","nkeditor":{"theme":"black"}}    };
    </script>

    <style type="text/css">
        body {
            color:#999;
            background:url('https://cdn.demo.fastadmin.net/assets/img/loginbg.jpg');
            background-size:cover;
        }
        a {
            color:#fff;
        }
        .login-panel{margin-top:150px;}
        .login-screen {
            max-width:400px;
            padding:0;
            margin:100px auto 0 auto;

        }
        .login-screen .well {
            border-radius: 3px;
            -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: rgba(255,255,255, 0.2);
        }
        .login-screen .copyright {
            text-align: center;
        }
        @media(max-width:767px) {
            .login-screen {
                padding:0 20px;
            }
        }
        .profile-img-card {
            width: 100px;
            height: 100px;
            margin: 10px auto;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }
        .profile-name-card {
            text-align: center;
        }

        #login-form {
            margin-top:20px;
        }
        #login-form .input-group {
            margin-bottom:15px;
        }

    </style>
</head>
<body><div id="video_wrapper">
    <video autoplay muted loop>
        <source src="https://t1.aixinxi.net/o_1cnh2rofs19skpcq11u6g921qm9a.mp4" type="video/mp4">
    </video>
</div>
<style>
    body {
        background:url("https://wx2.sinaimg.cn/large/0060lm7Tly1fvblyjxs3dj31hc0u0tc1.jpg");
        background-size:cover;
    }
    #video_wrapper {
        margin: 0px;
        padding: 0px;
    }

    #video_wrapper video {
        position: fixed;
        top: 50%;
        left: 50%;
        z-index: -100;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
    }
</style>

<div class="container">
    <div class="login-wrapper">
        <div class="login-screen">
            <div class="well">
                <div class="login-form">
                    <img id="profile-img" class="profile-img-card" src="https://cdn.demo.fastadmin.net/assets/img/avatar.png" />
                    <p id="profile-name" class="profile-name-card"></p>
                    <p id="profile-name" class="profile-name-card" style="color:#fff;">
                        <!--用户名:admin 密码:123456-->
                        紫琼棋牌管理后台
                    </p>
                    <form action="" method="post" id="login-form">
                        <div id="errtips" class="hide"></div>
                        <?php echo token(); ?>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                            <input type="text" class="form-control" id="pd-form-username" placeholder="<?php echo __('Username'); ?>" name="username" autocomplete="off" value="" data-rule="<?php echo __('Username'); ?>:required;username" />
                        </div>

                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                            <input type="password" class="form-control" id="pd-form-password" placeholder="<?php echo __('Password'); ?>" name="password" autocomplete="off" value="" data-rule="<?php echo __('Password'); ?>:required;password" />
                        </div>
                        <?php if($config['fastadmin']['login_captcha']): ?>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></div>
                            <input type="text" name="captcha" class="form-control" placeholder="<?php echo __('Captcha'); ?>" data-rule="<?php echo __('Captcha'); ?>:required;length(4)" />
                            <span class="input-group-addon" style="padding:0;border:none;cursor:pointer;">
                                        <img src="<?php echo rtrim('/fastadmin_revision1/public/', '/'); ?>/captcha" width="100" height="30" onclick="this.src = '<?php echo rtrim('/fastadmin_revision1/public/', '/'); ?>/captcha?r=' + Math.random();"/>
                                    </span>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="inline" for="keeplogin">
                                <input type="checkbox" name="keeplogin" id="keeplogin" value="1" />
                                <?php echo __('Keep login'); ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg btn-block"><?php echo __('Sign in'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- FastAdmin是开源程序，建议在您的网站底部保留一个FastAdmin的链接 -->
            <p class="copyright"><a href="#">@~成都农易农科技有限公司</a></p>
        </div>
    </div>
</div>
<script src="https://cdn.demo.fastadmin.net/assets/js/require.min.js" data-main="https://cdn.demo.fastadmin.net/assets/js/require-backend.min.js?v=20181119"></script>
</body>
</html>
