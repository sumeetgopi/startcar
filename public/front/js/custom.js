jQuery(document).ready(function($) {	

/* Header Fixed  */
		$(window).scroll(function() {
			if ($(this).scrollTop() > 1) {
				$('header').addClass("header-small");
			} else {
				$('header').removeClass("header-small");
			}
		});		

/* testimonial-slider */
    $("#testimonial-slider").owlCarousel({
        items:1,
		loop:true,
		dots:true,
        autoPlay:true
    });
	
/*  Gallery  */
	$('[data-fancybox="gallery"]').fancybox({
		loop: true,
		keyboard: true,
		arrows: true,
		buttons: ["close"],
		animationEffect: "zoom",
		transitionEffect: "slide",
		transitionDuration: 500,
		protect: true
	});	

/* Fancybox */
   $('.poppup-forms.login-form, .poppup-forms.sign-upform').fancybox({
		prevEffect : 'fade',
		nextEffect : 'fade'
	 });
 
});