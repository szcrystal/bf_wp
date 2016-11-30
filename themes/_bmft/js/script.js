(function($) {

var szcExec = (function() {

	return {
    
		opts: {
            crtClass: 'crnt',
            btnID: '.top_btn',
            all: 'html, body',
            animEnd: 'webkitAnimationEnd MSAnimationEnd oanimationend animationend', //mozAnimationEnd
            transitEnd: 'webkitTransitionEnd MSTransitionEnd otransitionend transitionend', //mozTransitionEnd 
        },
        
		addCurrent: function() {
        	var url = window.location.href;
            var urls = url.split('/'); //explode
                        
            if(urls[3] == 'wordpress') {
        	//if(urls.length > 5) {
            	url= urls[0] + '//' + urls[2] + '/' + urls[3] + '/' + urls[4] + '/';
            }
            else {
	            url= urls[0] + '//' + urls[2] + '/' + urls[3] + '/';
    		}
                    
            $('.main-navigation a[href^="'+url+'"]').addClass(this.opts.crtClass);
        },
        
        
        scrollFunc: function() {
            var t = this,
                tb = $(t.opts.btnID);
            
            tb.css('display','none').on('click', function() {
                $(t.opts.all).animate({ scrollTop:0 }, 1200, 'easeOutExpo');
            });

            $(document).scroll(function(){

                if($(this).scrollTop() < 200)
                    tb.fadeOut(200);
                else 
                    tb.fadeIn(300);
            });
            
        },
        
        
        isAgent: function(user) {
            if( navigator.userAgent.indexOf(user) > 0 ) return true;
        },
        
        isLocal: function() {
        	if( location.port == 8006 ) return true;
        },
        
        isSpTab: function(arg) {

        	var spArr = ['iPhone','iPod','Mobile ','Mobile;','Windows Phone','IEMobile'];
            var tabArr = ['iPad','Kindle','Sony Tablet','Nexus 7','Android Tablet'];
            var arr = [];
            
            if(arg == 'sp')
            	arr = spArr;
            
            else if(arg == 'tab')
            	arr = tabArr;
            
            else
            	arr = spArr.concat(tabArr);
            
        	
            var th = this;
            var bool = false;
            
            arr.forEach(function(e, i, a) {
            	if(th.isAgent(e)) {
                	bool = true;
                    return; //Exit
                }
            });
            
            return bool;
        },
        
        
        setAspectForPc: function(ww, hh, aspect) {
        	if(aspect > -115) { //over Aspect > set height, org:-55
                $('.top #mainMv').css({height:hh,width:'auto'});
                console.log('aspect big'); //下切れの時
            }
            else {
            	$('.top #mainMv').css({width:'105%',height:'auto'});
                console.log('aspect small');
            }
        },
        
        setAspectForSp: function(ww, hh, aspect) {
        	if(aspect > 25) { //over Aspect > set height, org:-55
                $('#mainMv').css({backgroundSize:'auto '+ hh+'px'});
                console.log('aspect big'); //下切れの時
            }
            else {
            	$('#mainMv').css({backgroundSize:'105% auto'});
                console.log('aspect small');
            }
            
        },
        
        settingSize: function() {
        	
            var th = this;
        	
            var ww = $(window).width();
            var hh = $(window).height();
            var aspect = hh - (ww/3)*2; //over Aspect 3:2
            
//            var vw = $('#page').width();
//            vw = (vw - ww)/2;
            
            $('.load').css({height:hh}); //load
            
            if(szcExec.isSpTab('all')) {
            	szcExec.setAspectForSp(ww, hh, aspect);
            }
            else {
            	szcExec.setAspectForPc(ww, hh, aspect);
            }
            
            var $ctr = $('.ctr');
            var $ent = $('.ent');
            var cw = $ctr.width();
            var ch = $ctr.height();
            
            var ew = $ent.width();
            var eh = $ent.height();
            
            var adjust = szcExec.isSpTab('all') ? 0 : 25;
            
            $ent.css({left:ww/2-(ew/2)+10, top:hh/2-(eh/2)-adjust});
            $ctr.css({left:ww/2-(cw/2)+10, top:hh/2-(ch/2)-adjust});
            
            $('#mainMv:hidden').fadeIn(100);
            
        },
        
        
        setWindowResize: function(){
        	$(window).on('resize', this.settingSize);
            
            
            $('.tgl').on('click', function(){
            	$('.main-navigation').slideToggle(300);
            });
            
            /*
            $('#page').on('click', function(e){
            	if(! $(e.target).hasClass('main-navigation') && ! $(e.target).hasClass('fa')) {
            		$('.main-navigation:visible').slideUp(300);
                }
            });
			*/
            
        },
        
        contentPosi: function() {
            
            //Video And Loader ---------------------
            if($('body').hasClass('home') && ! this.isSpTab('all')) {
                var video = document.getElementById('mainMv');
                //var video = $('#mainMv').get(0);
                
                video.addEventListener('playing', function(){ //This is Not In window.load()
                    
                    //console.log(video.currentTime);

                    $load = $('.load');
                    
                    setTimeout(function(){
                        $load.find('.ent').fadeOut(800, 'swing', function(){
                            $load.fadeOut(2000, 'linear', function(){
                                setTimeout(function(){
                                    $('.ctr').fadeIn(2500);
                                }, 1500);
                            });
                        });
                    }, 2500);
                    
                }, false);
            
            
            	$(window).on('load', function(){
                    video.play();                    
            	});
            }
           
        },
        

        historyLink: function() {
        
        	$('.ctr .fa').on('click', function(){
            	
//                var data = {
//                	prev_title: document.title,
//                    prev_url: location.pathname,
//                };
                
                var href = location.href;
            
            	history.pushState(null, 'ソラシードとは', href+'about/');
            	
                $('.ctr').fadeOut(500, function() {
                    $('body').fadeOut(700, 'linear', function(){
                    	
                        //$('.subgif').fadeTo(300);
                        
                        $(this).after('<img src="' + href + 'wp-content/themes/_ss/images/6.gif" class="subgif">');
                        $('img.subgif').css({top:$(window).height()/2});

                    	//location.reload();
                        $(this).load(href+'about/', function(){
                            
                            $('#page.about').hide();
                            
                            var title = $(this).find('title').text();
                            var link = $(this).find('link[href*="nextend"]');
                            $('head > title').text(title);
                            $('head').append(link);
                            //'<link rel="stylesheet" type="text/css" href="http://192.168.10.17:8006/wp-content/cache/nextend/web/n2-ss-1/n2-ss-1.css?1468130069" media="screen, print" />'
                            
                            $(this).children('meta, script, link, style, title').remove();
                            
                            $(this).removeClass('home page-template-index page-template-index-php').addClass('page-template-default').fadeIn(400, function() {
                            	$(this).siblings('.subgif').remove();
                                $('#page.about').fadeIn(1000);
                            });
                        });
                    
                    });
                });
                
                
                return false;
            });
        },
        
        
        put: function(tag, argText) {
            $(tag).text(argText);
            console.log("CheckText is『 %s 』" , argText);
        },
        
        clickFade: function() {
        	$('.eng').on('click', function(){
            	$('.webd-sec:visible, .ope-sec:visible').fadeOut(200, function(){
                	$('.eng-sec').fadeIn(200, function(){
                    	$(this).queue([]).stop();
                    });
                });
            });
            
        	$('.web-d').on('click', function(){
            	$('.eng-sec:visible, .ope-sec:visible').fadeOut(200, function(){
                	$('.webd-sec').fadeIn(200);
                });
            });
            
            $('.ope').on('click', function(){
            	$('.eng-sec:visible, .webd-sec:visible').fadeOut(200, function(){
                	$('.ope-sec').fadeIn(200);
                });
            });
        },
        
        loginToggle: function() {
        	$('.showTgl').on('click', function(){
            	$(this).next('div').toggle();
            });
        	
        },
        
        
    } //return

})();


$(function(e){ //ready
    
//    szcExec.settingSize();
//    szcExec.contentPosi();
//
//    szcExec.historyLink();
//    
//    szcExec.setWindowResize();
//    
//    szcExec.addCurrent(); 
//    szcExec.clickFade();
  
    szcExec.scrollFunc();
  
    szcExec.loginToggle();
    
});


/* Easing */
$.easing['jswing'] = $.easing['swing'];

$.extend( $.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert($.easing.default);
		return $.easing[$.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - $.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return $.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return $.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

})(jQuery);
