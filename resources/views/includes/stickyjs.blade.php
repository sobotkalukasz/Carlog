<script>

		$(document).ready(function() {
	   var stickyNavTop = $('.nav').offset().top;

	   var stickyNav = function(){
	   var scrollTop = $(window).scrollTop();

	   if (scrollTop > stickyNavTop) {
	      $('.nav').addClass('sticky');
				$('.container').css('padding-top', '50px');
	   } else {
	      $('.nav').removeClass('sticky');
				$('.container').css('padding-top', 0);
	    }
	   };

	   stickyNav();

	   $(window).scroll(function() {
	      stickyNav();
	   });
	   });

</script>
