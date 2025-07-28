jQuery(document).ready(function($){
	function equal_height(el) {
	  var height_bregin = 0;
	  el.each(function () {
			if ($(this).height() > height_bregin) {
				height_bregin = $(this).height();
			}
	  }).height(height_bregin);
	}
	function resize_func(){
	  if($(window).width()>991){
			$(".content-page .col-md-10.col-xs-12 > div").css("min-height",$(".sidebar").height());
	  } else{
			$(".language").after($("#searchform"));
			$(".footer").before($(".banners"));
	  }
	  if(($(window).width() < 991) && ($(window).width() > 480)){
			$(".logo > a").after($(".btn-cart"));
			$(".logo > a:not(.btn-cart)").before($(".header .phone"));
	  }
	  if($(window).width()>767){
			equal_height($(".product-item-shop .image-link"));
			equal_height($(".shop .content .products .product-item-shop__desc"));
			equal_height($(".category .content .products .product-item-image .title"));
			equal_height($(".category .content .products .product-item-image"));
	  }
	  if($(window).width() < 480){
			$(".language").after($(".btn-cart"));
	  }
	}
	$(window).resize(function(){
	  resize_func();
	});

	$('img').each((i, item) => {
		const isAlt = $(item).attr('alt')

		if (!isAlt) {
			$(item).attr('alt', 'image')
		}
	})

	$('form.add-to-cart-wrap').on('submit', function (e) {
		e.preventDefault();

		var $form = $(this);
		var productID = $form.find('button[name="add-to-cart"]').val();
		var quantity = $form.find('input.qty').val();

		var data = {
			action: 'woocommerce_ajax_add_to_cart',
			product_id: productID,
			quantity: quantity
		};

		$(document.body).trigger('adding_to_cart', [$form, data]);

		$.post(wc_add_to_cart_params.ajax_url, data, function (response) {
			if (response.error && response.product_url) {
				window.location = response.product_url;
				return;
			}

			// Trigger WooCommerce cart fragments update
			$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $form]);
		});
	});

	if($("#shipping_method_0_free_shipping3").length > 0){
		$("#shipping_method_0_flat_rate4").parent().hide();
	} else{
		$("#shipping_method_0_flat_rate4").parent().show();
	}
	$( document.body ).on( 'updated_cart_totals', function(){
	if($("#shipping_method_0_free_shipping3").length > 0){
		$("#shipping_method_0_flat_rate4").parent().hide();
	}  else{
		$("#shipping_method_0_flat_rate4").parent().show();
	}
	});

	function initImage() {
		$('.mfp-img').apImageZoom({
			// autoCenter: false,
				// loadingAnimation: 'throbber',
			// maxZoom: false,
			// hammerPluginEnabled: false,
			//hardwareAcceleration: false,
			cssWrapperClass: 'custom-wrapper-class',
			minZoom: 'contain',
			maxZoom: 2.0
		});
	};
	$('#popup_img2').apImageZoom({
		// autoCenter: false,
		// loadingAnimation: 'throbber',
		// maxZoom: false,
		// hammerPluginEnabled: false,
		// hardwareAcceleration: false,
		maxZoom: 2.0,
		minZoom: 'contain',
		cssWrapperClass: 'custom-wrapper-class'
	});

	var lang_separate = $(".language .language-chooser li").eq(1).find("a").find("span");
	lang_separate.text(' / '+lang_separate.text());



	$(".toggle_mnu .sandwich").bind("click",function(){
		$(this).toggleClass("active");
		$(".content-page .right-menu > ul").slideToggle();
	});
	$(".popup").magnificPopup({
		type : "image",
		callbacks: {
			open: function() {
				initImage();
			},
			close: function() {
				// Will fire when popup is closed
			}
		}
	});
	$(".product_img").magnificPopup({
		type : "image"
	});
	$(".zoom").click(function(){
		var src = $("#popup_img").attr("src");
		var src1 = $("#popup_img1").attr("src");
		$.magnificPopup.open({
			items: {
				src: src
			},
			type : "image",
			callbacks: {
				open: function() {
					initImage();
				},
				close: function() {
				// Will fire when popup is closed
				}
			}
		});
		$.magnificPopup.open({
			items: {
				src: src1
			},
			type : "image",
			callbacks: {
				open: function() {
					initImage();
				},
				close: function() {
				// Will fire when popup is closed
				}
			}
		});
	});
	$("#question").each(function () {
		var i = $(this);
		$(i).validate({
			rules: {
				name_user: {
					required: true
				},
				email_user: {
					required: true,
					mail: true
				}
			},
			messages: {},
			//errorPlacement: function(error, element){},
			submitHandler: function submitHandler(form) {
			var thisForm = $(i);
			$.ajax({
				type: 'POST',
				url: "/wp-admin/admin-ajax.php",
				data: 'action=zakaz&' + thisForm.serialize(),
				success: function success(data) {
					$(thisForm).trigger("reset");
					$(thisForm).addClass('success');
					$("#question .thanks").slideToggle();
				}
			});
			},
			success: function success(label) {
				label.text('').addClass('valid');
			},
			highlight: function highlight(element, errorClass) {
				$(element).addClass('err');
			},
			unhighlight: function unhighlight(element, errorClass, validClass) {
				$(element).removeClass('err');
			}
		});
	});
	if($("body").hasClass("ltr")){
		var rtl = false;
	} else {
		var rtl = true;
	}
	$('.home-banner-slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		rtl: rtl,
		arrows: false,
		focusOnSelect: true,
		fade: true,
		infinite: true,
		autoplay: false,
		autoplaySpeed: 3000,
		adaptiveHeight: true
	});

	$('.banners').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		rtl: rtl,
		arrows: false,
		focusOnSelect: false,
		autoplay: true,
		autoplaySpeed: 5000
	});

	$('.category-shop-slider .products').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		rtl: rtl,
		arrows: false,
		adaptiveHeight: true
	});

	$('.slider-shop').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		dots: false,
		rtl: rtl,
		arrows: false,
		responsive: [{
			breakpoint: 1660,
			settings: {
			slidesToShow: 3,
			slidesToScroll: 1
			}
		},
		{
			breakpoint: 1400,
			settings: {
			slidesToShow: 2,
			slidesToScroll: 1
			}
		},
		{
			breakpoint: 600,
			settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true
			}
		}]
	});

	$('.slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		rtl: rtl,
		arrows: false,
		autoplay: true,
		autoplaySpeed: 5000,
		pauseOnFocus: false,
		pauseOnHover: false
	});
	$('.slider').on('afterChange', function(event, slick, currentSlide, nextSlide){
		$(".slider-image").height($(".content-page .slider-wrap .slider").height());
	});

	// #region Accessibility
	$('.slick-track').each((i, item) => {
		$(item).attr({
			'aria-label': `Slick track-${i}`,
			'tabindex': '0'
		});
	});
	$('.slick-dots').each((i, item) => {
		$(item).attr({
				'role': 'tablist',
		});
	});
	$('.slick-slide').each((i, item) => {
		$(item).attr({
				'role': 'option',
		});
	});
	// #endregion

	// menu
	$(".menu-item-has-children > a").after("<span>^</span>");
	$("body").on("click",".menu-item-has-children > span", function (e) {
		$(this).parent().find("ul").slideToggle();
	});
	$(".slider-image").height($(".content-page .slider-wrap .slider").height());
	$(".menu-wrap-top li").each(function(){
		if($(this).hasClass("current-menu-wrap-top__item")){
			$(this).parent().show();
		}
	});
	$(".close-popup").click(function(e){
		e.preventDefault();
		$.magnificPopup.close();
	});
	$(".faq-list-item__title").click(function(){
		$(this).next().fadeToggle();
		$(this).find(".slide-icon").toggleClass("active");
		$(this).find(".slide-icon").find("span").each(function(){
			if($(this).hasClass("hide")){
				$(this).fadeIn();
				$(this).removeClass("hide");
			} else{
				$(this).hide();
				$(this).addClass("hide");
			}
		});
	});
	resize_func();
	jQuery( "body" ).on("click",".ajax-update-cart a",function(e){
		var thiss = $(this);
		e.preventDefault();
		var count = $(this).parent().find("input");
		if ($(this).hasClass("plus")){
			var new_count = +count.val() + 1;
			count.val(new_count);
		} else {
			if (count.val() > 1) {
				var new_count = +count.val() - 1;
				count.val(new_count);
			}
		}
			$.post('/wp-admin/admin-ajax.php','action=set_quantity_custom&new_count='+ new_count + '&id='+ count.attr('data-id'),function(data){
				$("#test2").load(location.href+" #test2 > *");
				$(".cart-header").load(location.href+" .cart-header > *");
			});
	});

	jQuery(document).on('click', '.add-to-cart', function (e) {
		e.preventDefault();

		var $button = jQuery(this);
		var product_id = $button.data('product_id') || $button.val();
		var quantity = $button.closest('form').find('input.qty').val() || 1;

		// Trigger WC native AJAX
		jQuery.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
			product_id: product_id,
			quantity: quantity,
		}, function (response) {
			if (!response || !response.fragments) return;

			// Replace fragments (cart count, mini cart, etc.)
			jQuery.each(response.fragments, function (key, value) {
				jQuery(key).replaceWith(value);
			});

			// Your custom popup
			$.magnificPopup.open({
				items: {
					src: '#thanks'
				},
				type: 'inline'
			});
		});
	});

	jQuery( ".add_wishlist" ).each(function() {});
	jQuery( ".add_wishlist" ).click(function(e) {
		var product_id = jQuery(this).attr('data-product-id');
		var el = jQuery(this);
		e.preventDefault();
		add_wishlist(product_id);
		return false;
	});
	function add_wishlist(p_id) {
		$.magnificPopup.open({
			items: {
			src: '#thanks_wishlist'
		},
			type : "inline"
		});
		$.get(location.href+'/?add_to_wishlist=' + p_id, function() {});
	}

	function addToCart(p_id) {
		$.get('/?post_type=product&add-to-cart=' + p_id+'&quantity='+$("#quantity").val(), function() {
			$.magnificPopup.open({
			items: {
				src: '#thanks'
			},
			type : "inline"
			});
			$("#test2").load(location.href+" #test2 > *");
			$(".cart-header").load(location.href+" .cart-header > *");
		});
	}
	// $(".header .top-line .main-menu ul .sub > a").click(function (e) {
	//   e.preventDefault();
	// });
	$(".quantity .plus").click(function () {
		var count = $(this).parent().find("input");
		var new_count = +count.val() + 1;
		count.val(new_count);
	});
	$(".quantity .minus").click(function () {
		var count = $(this).parent().find("input");
		if (count.val() > 1) {
			var new_count = +count.val() - 1;
			count.val(new_count);
		}
	});
	$("body .mfp-close-btn-in .mfp-image-holder figure img").click(function(){
		$(this).toggleClass('active');
	});
	// initMap() - функция инициализации карты
	if (window.google) {
	function initMap() {
		// Координаты центра на карте. Широта: 56.2928515, Долгота: 43.7866641
		var centerLatLng = new google.maps.LatLng(32.485284, 34.945482);
		// Обязательные опции с которыми будет проинициализированна карта
		var mapOptions = {
			center: centerLatLng, // Координаты центра мы берем из переменной centerLatLng
			zoom: 13               // Зум по умолчанию. Возможные значения от 0 до 21
		};
		// Создаем карту внутри элемента #map
		var map = new google.maps.Map(document.getElementById("map"), mapOptions);
		var marker = new google.maps.Marker({
			position: centerLatLng,              // Координаты расположения маркера. В данном случае координаты нашего маркера совпадают с центром карты, но разумеется нам никто не мешает создать отдельную переменную и туда поместить другие координаты.
			map: map                            // Карта на которую нужно добавить маркер
		});
	}
	// Ждем полной загрузки страницы, после этого запускаем initMap()
	window.addEventListener("load", initMap);
	}
});
