jQuery(function($) {
	
	$.fn.randomize = function(selector){
	    var $elems = selector ? $(this).find(selector) : $(this).children(),
	        $parents = $elems.parent();
	
	    $parents.each(function(){
	        $(this).children(selector).sort(function(){
	            return Math.round(Math.random()) - 0.5;
	        // }). remove().appendTo(this); // 2014-05-24: Removed `random` but leaving for reference. See notes under 'ANOTHER EDIT'
	        }).detach().appendTo(this);
	    });
	
	    return this;
	};	
	
	$('.slides.random').randomize();	
	
	$('.slides').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,
		fade: true,
		cssEase: 'linear',
		autoplay: true,
		pauseOnHover: false,
		autoplaySpeed: 7000,
		infinite: true
	});

	$('.home-prev-photo').click(function(){
		$('.home .slick-prev')[0].click();
	}); 
	$('.home-next-photo').click(function(){
		$('.home .slick-next')[0].click();
	});			
});
				