(function ($) {
"use strict";

	jQuery("#bgndVideo").YTPlayer();

	var win = $(window);
	win.on('load', function () {
		$('#preloader').delay(350).fadeOut('slow');
		$('body').delay(350).css({ 'overflow': 'visible' });
	})


	/*------------------------------------
		Mobile Menu
	--------------------------------------*/

	$('#mobile-menu-active').metisMenu();

	$('#mobile-menu-active .has-dropdown > a').on('click', function (e) {
		e.preventDefault();
	});

	$(".hamburger-menu > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").toggleClass("show");
		$("body").addClass("on-side");
		$('.body-overlay').addClass('active');
		$(this).addClass('active');
	});

	$(".close-mobile-menu > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").removeClass("show");
		$("body").removeClass("on-side");
		$('.body-overlay').removeClass('active');
		$('.hamburger-menu > a').removeClass('active');
	});

	$('.body-overlay').on('click', function () {
		$(this).removeClass('active');
		$(".slide-bar").removeClass("show");
		$("body").removeClass("on-side");
		$('.hamburger-menu > a').removeClass('active');
	});

	// header - sticky
	win.on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 245) {
			$(".header-sticky").removeClass("sticky");
		} else {
			$(".header-sticky").addClass("sticky");
		}
	});

	// data - background
	$("[data-background]").each(function () {
		$(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
	})

	$("[data-bg-color]").each(function () {
		$(this).css("background", $(this).attr("data-bg-color"))
	})

	/* magnificPopup img view */
	$('.popup-image').magnificPopup({
		type: 'image',
		gallery: {
			enabled: true
		}
	});

	/* magnificPopup video view */
	$('.popup-video').magnificPopup({
		type: 'iframe'
	});

	// mainSlider
	function mainSlider() {
		var BasicSlider = $('.slider-active');
		BasicSlider.on('init', function (e, slick) {
			var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
			doAnimations($firstAnimatingElements);
		});
		BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
			var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
			doAnimations($animatingElements);
		});
		BasicSlider.slick({
			autoplay: false,
			autoplaySpeed: 10000,
			dots: true,
			fade: true,
			arrows: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
					}
				},
				{
					breakpoint: 991,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						arrows: false
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						arrows: false
					}
				}
			]
		});

		function doAnimations(elements) {
			var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			elements.each(function () {
				var $this = $(this);
				var $animationDelay = $this.data('delay');
				var $animationType = 'animated ' + $this.data('animation');
				$this.css({
					'animation-delay': $animationDelay,
					'-webkit-animation-delay': $animationDelay
				});
				$this.addClass($animationType).one(animationEndEvents, function () {
					$this.removeClass($animationType);
				});
			});
		}
	}
	mainSlider();


    $('.slider-active-three').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-long-arrow-alt-left"></i><span>next</span></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-long-arrow-alt-right"></i><span>prev</span></button>',
		slidesToShow: 1,
		slidesToScroll: 1,
     });

    $('.dis-img-active').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fal fa-chevron-right"></i></button>',
		slidesToShow: 1,
		slidesToScroll: 1,
     });

	// brand - active
	$('.brand-active').slick({
		dots: false,
		arrows: false,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
		slidesToShow: 5,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 1,
					infinite: true,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			}
		]
	});

    // product - activation

	$('.product-active').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-long-arrow-alt-left"></i><span>next</span></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-long-arrow-alt-right"></i><span>prev</span></button>',
		slidesToShow: 4,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 4,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
				}
			}
		]
	});


    // product - activation
	$('.product-deals-five-active').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-long-arrow-alt-left"></i><span>next</span></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-long-arrow-alt-right"></i><span>prev</span></button>',
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
				}
			}
		]
	});

    //kitchen product activation

    $('.kitchen-pro-active').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-long-arrow-alt-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-long-arrow-alt-right"></i></button>',
		slidesToShow: 1,
		slidesToScroll: 1,
     });

    // categories activation

	$('.categories-active').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fal fa-chevron-right"></i></button>',
		slidesToShow: 8,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 6,
					slidesToScroll: 1,
					infinite: true,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
            {
				breakpoint: 575,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	/* testimonial-active */
	$('.testimonial-active').slick({
		dots: true,
		arrows: false,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fal fa-chevron-right"></i></button>',
		slidesToShow: 1,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
            {
				breakpoint: 575,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	// blog - active
	$('.postbox__gallery').slick({
		dots: false,
		arrows: true,
		infinite: true,
		speed: 300,
		prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
		slidesToShow: 1,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});

// isotop
$('.blog-masonry').imagesLoaded(function () {
	// init Isotope
	var $grid = $('.blog-masonry').isotope({
		itemSelector: '.grid-item',
		percentPosition: true,
		masonry: {
			// use outer width of grid-sizer for columnWidth
			columnWidth: '.grid-item',
		}
	});
});

// isotop
$('.grid').imagesLoaded( function() {
	// init Isotope
	var $grid = $('.grid').isotope({
	  itemSelector: '.grid-item',
	  percentPosition: true,
	  masonry: {
		// use outer width of grid-sizer for columnWidth
		columnWidth: '.grid-item',
	  }
	});

	// filter items on button click
	$('.portfolio-menu').on('click', 'button', function () {
		var filterValue = $(this).attr('data-filter');
		$grid.isotope({ filter: filterValue });
	});
});


//for menu active class
$('.portfolio-menu button').on('click', function(event) {
	$(this).siblings('.active').removeClass('active');
	$(this).addClass('active');
	event.preventDefault();
});


// scrollToTop
$.scrollUp({
	scrollName: 'scrollUp', // Element ID
	topDistance: '300', // Distance from top before showing element (px)
	topSpeed: 300, // Speed back to top (ms)
	animation: 'fade', // Fade, slide, none
	animationInSpeed: 200, // Animation in speed (ms)
	animationOutSpeed: 200, // Animation out speed (ms)
	scrollText: '<i class="far fa-chevron-up"></i>', // Text for element
	activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
});

// WOW active
new WOW().init();

    /*----- cart-plus-minus-button -----*/
$(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
$(".qtybutton").on("click", function () {
	var $button = $(this);
	var oldValue = $button.parent().find("input").val();
	if ($button.text() == "+") {
		var newVal = parseFloat(oldValue) + 1;
	} else {
		// Don't allow decrementing below zero
		if (oldValue > 0) {
			var newVal = parseFloat(oldValue) - 1;
		} else {
			newVal = 0;
		}
	}
	$button.parent().find("input").val(newVal);
});

     //range slider activation

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function (event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));


    // count down
    $('[data-countdown]').each(function () {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(event.strftime('<div class="time-count">%D <span>days</span></div><div class="time-count">%H <span>hour</span></div><div class="time-count">%M <span>minute</span></div><div class="time-count">%S <span>Second</span></div>'));
        });
	});

	/*-------------------------
		showlogin toggle function
	--------------------------*/
	$('#showlogin').on('click', function () {
		$('#checkout-login').slideToggle(900);
	});

	/*-------------------------
		showcoupon toggle function
	--------------------------*/
	$('#showcoupon').on('click', function () {
		$('#checkout_coupon').slideToggle(900);
	});

	/*-------------------------
		Create an account toggle function
	--------------------------*/
	$('#cbox').on('click', function () {
		$('#cbox_info').slideToggle(900);
	});

	/*-------------------------
		Create an account toggle function
	--------------------------*/
	$('#ship-box').on('click', function () {
		$('#ship-box-info').slideToggle(1000);
	});

	/* Search
	-------------------------------------------------------*/
	var $searchWrap = $('.search-wrap');
	var $navSearch = $('.nav-search');
	var $searchClose = $('#search-close');

	$('.search-trigger').on('click', function (e) {
		e.preventDefault();
		$searchWrap.animate({ opacity: 'toggle' }, 500);
		$navSearch.add($searchClose).addClass("open");
	});

	$('.search-close').on('click', function (e) {
		e.preventDefault();
		$searchWrap.animate({ opacity: 'toggle' }, 500);
		$navSearch.add($searchClose).removeClass("open");
	});

	function closeSearch() {
		$searchWrap.fadeOut(200);
		$navSearch.add($searchClose).removeClass("open");
	}

	$(document.body).on('click', function (e) {
		closeSearch();
	});

	$(".search-trigger, .main-search-input").on('click', function (e) {
		e.stopPropagation();
	});

	//nice select activation
	$('.sort-wrapper select,.contact__form select').niceSelect();


})(jQuery);
