/**
 * @ Module name
 * @ authors: H.yingzi - h.yingzi@gmail.com
 * @ team: Digiocean - http://www.digiocean.cc
 * @ date:
 * @ version: 0.0.0
 *
 */
/*
    TODO:
*/

$(function() {

    var API_URL = "/volvo/"

    var HOST = ""

    var $app = $("#app"),
        $pop = $("#pop"),
        $tips = $("#tips"),
        $btn = $("#formBtn"),
        $form = $("#form"),
        $sign = $("#signBtn")

    var CLICK = "ontouchend" in document ? "touchend" : "click"

    var App = {
        debug:true,
        lockSwipe:true,
        init: function() {
            var _this = this

            this.loadAssets(function(o, time) {
                _this.swiper()
                _this.bindEvent()

                setTimeout(function() {
                    $pop.hide()
                    $app.addClass("show")
                }, 200)
            })
        },
        eachAssets: function() {
            var _this = this,
                imgs = []
            this.assetsList = {}

            $(".load").each(function(i, o) {
                var $this = $(this),
                    src = HOST + $this.data("src")

                imgs.push(src)
                _this.assetsList[i] = {
                    dom: $this,
                    url: src
                }
            })
            return imgs
        },
        loadAssets: function(callback) {
            var _this = this,
                imgs = this.eachAssets(),
                loadtext = 1,
                loadTotal = $(".load").length

            $.imgpreload(imgs, {
                thread: true,
                itemload: function(index, url, time, state) {
                    if(_this.debug){
                        console.log("Index:",index," - ", "Time:",time+"ms"," - ", "State:",state, Math.floor((loadtext / loadTotal) * 100) + "%"," - ",url)
                    }

                    $("#loadingText").text(Math.floor(((loadtext++) / loadTotal) * 100))

                    var dom = _this.assetsList[index].dom
                    if (dom.hasClass('loadbg')) {
                        dom.css("background-image", "url(" + url + ")")
                    } else {
                        dom.attr("src", url).addClass('loaded')
                    }
                    dom.removeAttr("data-src").removeClass("load")
                },
                allload: callback
            })
        },
        swiper: function() {
            var _this = this
            return this.appSwiper = $(".swiper-container").swiper({
                direction: 'vertical',
                onSliderMove: function() {

                },
                onTransitionEnd: function(s) {
                    var active = s.activeIndex

                    $tips[s.isEnd ? "addClass" : "removeClass"]("hide")
                    
                    if(active=="5"&&_this.lockSwipe){
                        $tips.addClass("hide")
                        s.lockSwipeToNext()
                    }else{
                        s.unlockSwipeToNext()
                    }
                },
                onTouchEnd: function(o) {

                }
            })
        },
        bindEvent: function() {
            var _this = this,
                formData = {}

            $sign.on(CLICK,function(){
                _this.appSwiper.slideTo(5)
            })

            $btn.on(CLICK, function() {

                if (check()) {
                    _this.submitData(formData)
                }
            })

            function check() {
                var emailReg = /[a-z0-9-]{1,30}@[a-z0-9-]{1,65}.[a-z]{3}/

                formData = {
                    company: $.trim($form.find("#i-company").val()),
                    name: $.trim($form.find("#i-name").val()),
                    position: $.trim($form.find("#i-position").val()),
                    phone: $.trim($form.find("#i-phone").val()),
                    email: $.trim($form.find("#i-mail").val())
                }

                if (formData.company === "") {
                    tips("请填写公司全称")
                    return false
                } else if (formData.name === "") {
                    tips("请填写您的姓名")
                    return false
                } else if (formData.position === "") {
                    tips("请填写您的职称")
                    return false
                } else if (formData.phone === "" || formData.phone.length !== 11) {
                    tips("请填写正确的电话号码")
                    return false
                } else if (formData.email === "" || !emailReg.test(formData.email)) {
                    tips("请填写正确的E-mail地址")
                    return false
                } else {
                    return true
                }
            }
        },
        submitData: function(obj) {
            var _this = this

            $pop.addClass('loading').show()

            $.post(API_URL+"/signup.php", obj, function(data) {

            }).done(function(data) {
                $pop.removeClass('loading').hide()
                if($.parseJSON(data).error_code=="0"){
                    $("#form").find("input").val("")
                    tips("报名成功！")
                    _this.lockSwipe = false
                    _this.appSwiper.unlockSwipeToNext()
                    _this.appSwiper.slideTo(6)
                }
            }).fail(function(a) {
                $pop.removeClass('loading').hide()
            })
        }
    }

    /**
     * 获取字符串的长度（中文2、英文1）
     * @param string
     * return string
     */
    function getStrLen(str) {
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            var c = str.charCodeAt(i);
            //单字节加1
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
                len++;
            } else {
                len += 2;
            }
        }
        return len;
    }

    function tips(msg) {
        alert(msg)
    }

    window.App = App

    App.init()

})
