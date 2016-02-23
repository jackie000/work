<?php
include 'check_weixin_auth.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>VOLVO</title>
    <script type="text/javascript">
        if (/Android (\d+\.\d+)/.test(navigator.userAgent)) {
            var version = parseFloat(RegExp.$1);
            if (version > 2.3) {
                var phoneScale = parseInt(window.screen.width) / 640;
                document.write('<meta name="viewport" content="width=640, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
            } else {
                document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
            }
        } else {
            document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
        }
    </script>
    <meta name="description" content="BY DIGIOCEAN">
    <meta name="author" content="DIGIOCEAN (http://www.digiocean.cc/)">
    <meta name="keywords" content="">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link rel="stylesheet" href="src/css/swiper.min.css">
    <link rel="stylesheet" href="src/css/app.css"></head>
<body>
    <div class="app" id="app">
        <div class="page" id="page">
            <div class="logo">
                <img data-src="src/images/logo.png" class="load" alt="" />
            </div>
            <img data-src="src/images/touch-tips.png" class="load touch-tips" id="tips" alt="">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide p1 load loadbg" data-src="src/images/p1-bg.jpg">
                        <img data-src="src/images/p1-title.png" class="load p1-title" alt="">
                        <div class="text">
                            <p>从最初到未来，</p>
                            <p>每一次回首、每一次思索、每一次展望，</p>
                            <p>只为了更好地传承</p>
                            <p>以我们的故事续写我们的传奇</p>
                        </div>
                    </div>
                    <!-- <div class="swiper-slide p2 load loadbg" data-src="src/images/p2-bg.jpg">
                        <img data-src="src/images/p2-title.png" class="load p2-title" alt="">
                        <div class="text">
                            <p>从最初到未来</p>
                            <p>From the past to the future</p>
                            <p>每一次回首、每一次思索、每一次展望</p>
                            <p>Every time we look back, think and look into the future</p>
                            <p>只为了更好地传承</p>
                            <p>Is for a better inheritance of our culture</p>
                            <p>以我们的故事续写我们的传奇</p>
                            <p>Written a legend in our story</p>
                        </div>
                    </div> -->
                    <div class="swiper-slide p3 load loadbg" data-src="src/images/p3-bg.jpg">
                        <img data-src="src/images/p3-1.png" class="load p3-1" alt="">
                        <img data-src="src/images/p3-2.png" class="load p3-2" alt="">
                    </div>
                    <div class="swiper-slide p4 load loadbg" data-src="src/images/p4-bg.jpg">
                        <img data-src="src/images/p4-1.png" class="load p4-1" alt="">
                    </div>
                    <div class="swiper-slide p5 load loadbg" data-src="src/images/p5-bg.jpg">
                        <img data-src="src/images/p5-1.png" class="load p5-1" alt="">
                    </div>
                    <div class="swiper-slide p6 load loadbg" data-src="src/images/p6-bg.jpg">
                        <img data-src="src/images/p6-title.png" class="load p6-title" alt="">
                        <img data-src="src/images/p6-1.png" class="load p6-1" alt="">
                        <img data-src="src/images/p6-2.png" class="load p6-2" id="signBtn" alt="">
                    </div>
                    <div class="swiper-slide p7 load loadbg" data-src="src/images/p7-bg.jpg">
                        <img data-src="src/images/p7-title.png" class="load p7-title" alt="">
                        <div class="line"></div>
                        <div class="table-div">
                            <div class="left">
                                <img data-src="src/images/p7-text.png" class="load p7-text" alt="">
                            </div>
                            <div class="right" id="form">
                                <ul>
                                    <li><input type="text" id="i-company"></li>
                                    <li><input type="text" id="i-name"></li>
                                    <li><input type="text" id="i-position"></li>
                                    <li><input type="tel" id="i-phone"></li>
                                    <li><input type="email" id="i-mail"></li>
                                </ul>
                            </div>
                            <img data-src="src/images/p7-btn.png" class="p7-btn load" id="formBtn" alt="">
                        </div>
                    </div>
                    <div class="swiper-slide p8 load loadbg" data-src="src/images/p8-bg.jpg">
                        <img data-src="src/images/p8-title.png" class="load p8-title" alt="">
                        <div class="p8-menu">
                            <a href="javascript:" data-src="src/images/p8-menu-bg.png" class="load loadbg p8-menu-1">酒店&班车/Hotel&Shuttle bus</a>
                            <a href="javascript:" data-src="src/images/p8-menu-bg.png" class="load loadbg p8-menu-2">日程安排/Agenda</a>
                            <a href="javascript:" data-src="src/images/p8-menu-bg.png" class="load loadbg p8-menu-3">City Tour</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pages" id="pages">
            <div class="pages-item pages-0 load loadbg" data-src="src/images/p6-bg.jpg">

            </div>
            <div class="pages-item pages-1 load loadbg" data-src="src/images/p7-bg.jpg">

            </div>
            <div class="pages-item pages-2 load loadbg" data-src="src/images/p8-bg.jpg">

            </div>
        </div>
    </div>

    <div class="pop" id="pop">
        <div class="mask"></div>
        <div id="cssload-wrapper">
        	<div class="cssload-loader">
        		<div class="cssload-line"></div>
        		<div class="cssload-line"></div>
        		<div class="cssload-line"></div>
        		<div class="cssload-line"></div>
        		<div class="cssload-line"></div>
        		<div class="cssload-line"></div>
        		<div class="cssload-subline"></div>
        		<div class="cssload-subline"></div>
        		<div class="cssload-subline"></div>
        		<div class="cssload-subline"></div>
        		<div class="cssload-subline"></div>
        		<div class="cssload-loader-circle-1"><div class="cssload-loader-circle-2"></div></div>
        		<div class="cssload-needle"></div>
        		<div class="cssload-loading" id="loadingText">loading</div>
        	</div>
        </div>
    </div>
    <script src="src/js/plugin.js"></script>
    <script src="src/js/app.js"></script>
</body>

</html>
