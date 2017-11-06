<!DOCTYPE html>
<html lang="en" ng-app="Ctf-Portal">
<head>
    <title><?php echo SITE_NAME ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content="Capture The Flag" name="description">
    <meta name="keywords"
          content="Capture The Flag, Jeopardy, Attack/Defense, Reverse, Pwnable, Exploit, Web Application Security, Cryptography, Computer Forensics">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <link rel="Shortcut Icon" href="<?php echo base_url('assets/favicon.ico') ?>" type="image/x-icon"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/component/bootstrap/css/bootstrap.css') ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/component/font-awesome/css/font-awesome.min.css') ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/component/angular-loading-bar/loading-bar.min.css') ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/component/toastr/toastr.min.css') ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>"/>
</head>
<body>
<header class="navbar navbar-inverse navbar-fixed-top" ng-controller="HeaderCtrl">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" ui-sref="home"><strong><?php echo SITE_NAME ?></strong></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if (Auth_logged()): ?>
                    <li ng-class="{active:$state.includes('challenge')}">
                        <a ui-sref="challenge"><span class="fa fa-flag"></span> Thử thách</a>
                    </li>
                <?php endif; ?>
                <li ng-class="{active:$state.includes('scoreboard')}">
                    <a ui-sref="scoreboard"><span class="fa fa-bar-chart"></span> Bảng điểm</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!Auth_logged()): ?>
                    <li ng-class="{active:$state.includes('sign-in')}"><a ui-sref="sign-in"><span
                                    class="fa fa-user"></span> Đăng nhập</a></li>
                <?php else : ?>
                    <li class="dropdown" ng-class="{active:$state.includes('user')}">
                        <a href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown'><span
                                    class="fa fa-user"></span> <?php echo Auth_username(); ?><span class='caret'></span></a>
                        <ul class='dropdown-menu'>
                            <li><a ui-sref="user">Tài khoản</a></li>
                            <li><a href="javascript:void(0);" ng-click="DoSignOut()">Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
<div class="container">
    <div id="content_wrapper">
        <div ncy-breadcrumb></div>
        <div id="content" ui-view></div>
    </div>
    <div class="footer">
        <div class="copyright">
            <p class="pull-left sm-pull-reset">
                <span>Copyright <span class="copyright">&copy;</span> 2017 </span>
                <span><?php echo SITE_NAME ?></span>.
            </p>
<!--            <p class="pull-right sm-pull-reset">-->
<!--                <span><a class="m-r-10" href="https://matesctf.org">Trang chủ</a> | <a class="m-l-10 m-r-10"-->
<!--                                                                                       href="https://facebook.com/matesctf">Fanpage</a> | <a-->
<!--                            class="m-l-10" href="http://forum.matesctf.org">Diễn đàn</a></span>-->
<!--            </p>-->
        </div>
    </div>
    <script>
        function base_url(url) {
            if (url !== undefined) {
                return '<?php echo base_url() ?>' + '/' + url;
            } else {
                return '<?php echo base_url() ?>';
            }
        }
        var TOKEN_NAME = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var TOKEN_VALUE = '<?php echo $this->security->get_csrf_hash(); ?>';</script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/jquery/jquery-3.2.1.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-1.6.6/angular.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-1.6.6/angular-ui-router.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-1.6.6/angular-breadcrumb.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-1.6.6/angular-sanitize.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-loading-bar/loading-bar.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-timer/angular-timer.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-timer/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-timer/locales.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/angular-timer/humanize-duration.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/component/toastr/toastr.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/app.js') ?>"></script>
</div>
</body>
</html>
