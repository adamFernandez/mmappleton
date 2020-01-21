$(function () { 
	// Stack initialize
	var openspeed = 500;
	var closespeed = 500;
	$('.stack>img').toggle(function(){
		var vertical = 0;
		var horizontal = 0;
		var $el=$(this);
		$el.next().children().each(function(){
			$(this).animate({top: vertical + 'px', left: horizontal + 'px'}, openspeed);
			vertical = vertical + 60;
			horizontal = (horizontal-1)*2;
		});
		$el.next().animate({top: '100px', left: '10px'}, openspeed).addClass('openStack')
		   .find('li a>img').animate({width: '40px', marginLeft: '9px'}, openspeed);
		$el.animate({paddingBottom: '0'});
	}, function(){
		//reverse above
		var $el=$(this);
		$el.next().removeClass('openStack').children('li').animate({top: '-70px', left: '-13px'}, closespeed);
		$el.next().find('li a>img').animate({width: '40px', marginLeft: '0'}, closespeed);
		$el.animate({paddingBottom: '35px'});
	});
	
	// Stacks additional animation
	$('.stack li a').hover(function(){
		$("img",this).animate({width: '45px', height:'45px'}, 300);
		$("span",this).animate({marginLeft: '40px', marginTop:'6px', opacity: '100', zIndex: '1', fontSize: '1.3em'}, 300);
	},function(){
		$("img",this).animate({width: '40px', height:'40px'}, 300);
		$("span",this).animate({marginTop: '0', marginLeft:'-5px', opacity: '0', zIndex: '1',fontSize: '0.7em'}, 1000);
		
	});
});