



$(document).ready(function(){

	$('#file-upload').change(function() {
		var i = $(this).prev('label').clone();
		var file = $('#file-upload')[0].files[0].name;
		$(this).prev('label').text(file);
	  });

    $('#autoparkFilterButton li a').click(function () {
		var ourClass = $(this).attr('class');
		$('#autoparkFilterButton li').removeClass('active');
		$(this).parent().addClass('active');
		if (ourClass == 'all') {
			$('#autopark-body').children('div.item').show();
		}
		else {
			$('#autopark-body').children('div:not(.' + ourClass + ')').hide();
			$('#autopark-body').children('div.' + ourClass).show();
		}


		return false;
	});

    $( '#galleryFilterButton li a' ).click( function()
    {
        var ourClass = $( this ).attr( 'class' );
        $( '#galleryFilterButton li' ).removeClass( 'active' );
        $( this ).parent().addClass( 'active' );
        if( ourClass == 'all' )
        {
            $( '#aniimated-thumbnials' ).children( 'a.media-item' ).show();
        } else
        {
            $( '#aniimated-thumbnials' ).children( 'a:not(.' + ourClass + ')' ).hide();
            $( '#aniimated-thumbnials' ).children( 'a.' + ourClass ).show();
        }
        return false;
    } );

    $( '#faqFilterButton li' ).click( function()
    {
        var ourClass = $( this ).attr( 'class' );
        $( '#faqFilterButton li' ).removeClass( 'active' );
        $( this ).addClass( 'active' );
        if( ourClass == 'all' )
        {
            $( '#faq-body' ).children( 'div.___' ).show();
        } else
        {
            $( '#faq-body' ).children( 'div.___:not(.' + ourClass + ')' ).hide();
            $( '#faq-body' ).children( 'div.___.' + ourClass ).show();
        }
        return false;
    } );

    $( '#campaignFilterButton li' ).click( function()
    {
        var ourClass = $( this ).attr( 'class' );
        $( '#campaignFilterButton li' ).removeClass( 'active' );
        $( this ).addClass( 'active' );
        $( '#campaigns-body' ).children( 'div.___:not(.' + ourClass + ')' ).hide();
        $( '#campaigns-body' ).children( 'div.___.' + ourClass ).show();
        return false;
    } );


	$('#yukdashima-body').children('div.item:not(.active)').hide();

	$('#yukdashimaFilterButton li a').click(function () {
		var ourClass = $(this).attr('class');
		$('#yukdashimaFilterButton li').removeClass('active');
		$(this).parent().addClass('active');

		if (ourClass == 'active') {
			$('#yukdashima-body').children('div.item').show();
			$('#yukdashima-body').children('div.item:not(.' + ourClass + ')').hide();
		}
		else {
			$('#yukdashima-body').children('div.item:not(.' + ourClass + ')').hide();
			$('#yukdashima-body').children('div.item.' + ourClass).show();
		}


		return false;
	});


	  function scrollEvent() {
		var hT = $('#about').offset().top,
		  hH = $('#about').outerHeight(),
		  wH = $(window).height(),
		  wS = $(this).scrollTop();
		if (wS > (hT+hH-wH)){
			  $('.count').each(function () {
				  $(this).prop('Counter',0).animate({
					  Counter: $(this).text()
					  }, {
					  duration: 1500,
					  easing: 'swing',
					  step: function (now) {
						  $(this).text(Math.ceil(now));

					  }

					  });

				  });
		  window.removeEventListener("scroll", scrollEvent);
		}
	  }
	  window.addEventListener("scroll", scrollEvent);





	$('#filterButton li a').click(function () {
		var ourClass = $(this).attr('class');
		$('#filterButton li').removeClass('active');
		$(this).parent().addClass('active');
		if (ourClass == 'all') {
			$('#question-blok').children('div.item').show();
		}
		else {
			$('#question-blok').children('div:not(.' + ourClass + ')').hide();
			$('#question-blok').children('div.' + ourClass).show();
		}


		return false;
	});





	$('.item').click(function () {
		$("i", this).toggleClass("fas fa-angle-right fas fa-angle-down");
	  });

	  $('.answer').click(function () {
		$('.item').find('i').toggleClass("fas fa-angle-right fas fa-angle-down");
	  });



	  $(".team-members__body--header").hover(function(){
		$(this).find(".info").toggleClass("d-flex");
	});




	$(function() {
		$( '.payment-item' ).on( 'click', function() {
			  $( this ).parent().find( '.active' ).removeClass( 'active' );
			  $( this ).addClass( 'active' );
		});
  });
});
// GUNEL JS FINISH 2nd version
// GUNEL JS START
var acc = document.getElementsByClassName("faq-item");
 var i;
 for (i = 0; i < acc.length; i++) {
	 acc[i].addEventListener("click", function () {
		 this.classList.toggle("active");
		 var answer = this.nextElementSibling;
		 if (answer.style.display === "block") {
			 answer.style.display = "none";
		 } else {
			 answer.style.display = "block";
		 }
	 });
 }
$(document).ready(function(){

	$('.faq-item').click(function () {
		$("i", this).toggleClass("fas fa-angle-right fas fa-angle-down");
	  });
	//   $('.answer').click(function () {
	// 	$('.faq-item').find('i').toggleClass("fas fa-angle-right fas fa-angle-down");
	//   });
	  $(".team-body__item--header").hover(function(){
		$(this).find(".team-body__item--over").toggleClass("d-flex");
	});
});



// GUNEL JS FINISH
$(function() {
	var wrapperWidth = $(".wrapper").width();
	var autoparkItemWidth = 272;
	var mediaItemWidth = 170;
	//	create AutoparkCarousel
	if(window.matchMedia('(min-width: 1200px)').matches){
		$('#autoparkCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 272,
				height: 588,
				visible: 4
			},
			auto: {
				items: 0
			},
			prev: '#autoparkPrev',
			next: '#autoparkNext'
		});
		var autoparkMargin = wrapperWidth - 4 * autoparkItemWidth;
		$(".main-autopark__body--item").css("margin-right", autoparkMargin/3);
	}
	else if(window.matchMedia('(min-width:992px) and (max-width: 1199px)').matches){
		$('#autoparkCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 272,
				height: 588,
				visible: 3
			},
			auto: {
				items: 0
			},
			prev: '#autoparkPrev',
			next: '#autoparkNext'
		});
		var autoparkMargin = wrapperWidth - 3 * autoparkItemWidth;
		$(".main-autopark__body--item").css({"margin-right": autoparkMargin/6, "margin-left": autoparkMargin/6});
	}
	else if(window.matchMedia('(min-width:768px) and (max-width: 991px)').matches){
		$('#autoparkCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 272,
				height: 588,
				visible: 2
			},
			auto: {
				items: 0
			},
			prev: '#autoparkPrev',
			next: '#autoparkNext'
		});
		var autoparkMargin = wrapperWidth - 2 * autoparkItemWidth;
		$(".main-autopark__body--item").css({"margin-right": autoparkMargin/4, "margin-left": autoparkMargin/4});
	}
	else{
		$('#autoparkCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 272,
				height: 588,
				visible: 1
			},
			auto: {
				items: 0
			},
			prev: '#autoparkPrev',
			next: '#autoparkNext'
		});
	}
	if(window.matchMedia('(min-width:576px) and (max-width:767px)').matches){
		$("#autoparkWrapper .caroufredsel_wrapper").css({"margin-right": "auto", "margin-left": "auto", "width": "272px"});
	}
	else if(window.matchMedia('(min-width:364px) and (max-width: 575px)').matches){
		$("#autoparkWrapper .caroufredsel_wrapper").css({"margin-right": "auto", "margin-left": "auto", "width": "245px"});
	}
	else if(window.matchMedia('(max-width:363px)').matches){
		$("#autoparkWrapper .caroufredsel_wrapper").css({"margin-right": "auto", "margin-left": "auto", "width": "228px"});
	}
	//	re-position the carousel, vertically centered
	var $elems = $('#autoparkWrapper'),
	$image = $('#autoparkCarousel div:first')
	$(window).bind( 'resize.example', function() {
	  	var height = $image.outerHeight( true );
	  	$elems
			.height( height );
	}).trigger( 'resize.example' );

	/*--------------------------------------------------- */

	//	create mediaCarousel
	if(window.matchMedia('(min-width: 1200px)').matches){
		$('#mediaCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 170,
				height: 278,
				visible: 5
			},
			auto: {
				items: 0
			},
			prev: '#mediaPrev',
			next: '#mediaNext'
		});
		var mediaMargin = wrapperWidth - 5 * mediaItemWidth;
		$(".main-media .news-item").css({"margin-right": mediaMargin/10, "margin-left": mediaMargin/10});
	}
	else if(window.matchMedia('(min-width:992px) and (max-width: 1199px)').matches){
		$('#mediaCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 170,
				height: 278,
				visible: 4
			},
			auto: {
				items: 0
			},
			prev: '#mediaPrev',
			next: '#mediaNext'
		});
		var mediaMargin = wrapperWidth - 4 * mediaItemWidth;
		$(".main-media .news-item").css({"margin-right": mediaMargin/8, "margin-left": mediaMargin/8});
	}
	else if(window.matchMedia('(min-width:768px) and (max-width: 991px)').matches){
		$('#mediaCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 170,
				height: 278,
				visible: 3
			},
			auto: {
				items: 0
			},
			prev: '#mediaPrev',
			next: '#mediaNext'
		});
		var mediaMargin = wrapperWidth - 3 * mediaItemWidth;
		$(".main-media .news-item").css({"margin-right": mediaMargin/6, "margin-left": mediaMargin/6});
	}
	else if(window.matchMedia('(min-width:576px) and (max-width: 767px)').matches){
		$('#mediaCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 170,
				height: 278,
				visible: 2
			},
			auto: {
				items: 0
			},
			prev: '#mediaPrev',
			next: '#mediaNext'
		});
		var mediaMargin = wrapperWidth - 2 * mediaItemWidth;
		$(".main-media .news-item").css({"margin-right": mediaMargin/4, "margin-left": mediaMargin/4});
	}
	else{
		$('#mediaCarousel').carouFredSel({
			responsive: true,
			items: {
				width: 170,
				height: 278,
				visible: 1
			},
			auto: {
				items: 0
			},
			prev: '#mediaPrev',
			next: '#mediaNext'
		});
		var mediaMargin = wrapperWidth - mediaItemWidth;
		$(".main-media .news-item").css({"margin-right":mediaMargin/2, "margin-left":mediaMargin/2});
	}
	//	re-position the carousel, vertically centered
	var $elems = $('#mediaWrapper'),
	$image = $('#mediaCarousel div:first')
	$(window).bind( 'resize.example', function() {
	  	var height = $image.outerHeight( true );
	  	$elems
			.height( height );
	}).trigger( 'resize.example' );
});

/*--------------------------------------------------- */

$(document).ready(function(){
	$(".header-primary__tab").click(function(){
		var cl = $(this).attr("class");
		var cl2 = cl.split(" ",2)[1];
		$("." +cl2).addClass("active");
		$(".header-primary__tab").not("." +cl2).removeClass("active");
		$(".footer-primary__tab").not("." +cl2).removeClass("active");
		$(".comments-header__tab").not("." +cl2).removeClass("active");
	});
	$(".header-language__dropdown").hover(function(){
		$(this).find(".header-dropdown__menu").css("display", "block");
	});
	$(".header-language__dropdown").mouseleave(function(){
		$(this).find(".header-dropdown__menu").css("display", "none");
	});
	$(".header-navbar__dropdown").hover(function(){
		$(this).find(".header-maindropdown__menu").css("display", "block");
	});
	$(".header-navbar__dropdown").mouseleave(function(){
		$(this).find(".header-maindropdown__menu").css("display", "none");
		$(".header-subdropdown__menu").css("display", "none");
	});
	$(".header-subdropdown").click(function(){
		$(this).find(".header-subdropdown__menu").css("display", "block");
		$(".header-subdropdown").not(this).find(".header-subdropdown__menu").css("display", "none");
	});
	$(".header-sidenav__item").click(function(){
		$(this).next().toggleClass("d-block");
		$(this).find("i").toggleClass("fa-chevron-up");
	});
	$(".header-sidenav__subitem").click(function(){
		$(this).next().toggleClass("d-block");
		$(this).find("i").toggleClass("fa-chevron-up");
	});
	$(".footer-primary__tab").click(function(){
		var cl = $(this).attr("class");
		var cl2 = cl.split(" ",2)[1];
		$("." +cl2).addClass("active");
		$(".footer-primary__tab").not("." +cl2).removeClass("active");
		$(".header-primary__tab").not("." +cl2).removeClass("active");
		$(".comments-header__tab").not("." +cl2).removeClass("active");
		// Valeh
		var id = $(this).attr('id');
		$('.main-comments__table').hide();
		$('.' + id).show();
	});
	$(".comments-header__tab").click(function(){
		var cl = $(this).attr("class");
		var cl2 = cl.split(" ",2)[1];
		$("." +cl2).addClass("active");
		$(".footer-primary__tab").not("." +cl2).removeClass("active");
		$(".header-primary__tab").not("." +cl2).removeClass("active");
		$(".comments-header__tab").not("." +cl2).removeClass("active");
		// Valeh
		let id = $(this).attr('id');
		$('.main-comments__table').hide();
		$('.' + id).show();
	});
	$(".service-item").hover(function(){
		$(this).find(".service-image--hover").toggleClass("d-flex");
	});
	/*$(".regulator-minus").click(function(){
		var output = parseInt($(this).parent().find(".regulator-output").val());
		if(output>0){
			$(this).parent().find(".regulator-output").val(output-1);
		}
	});
	$(".regulator-plus").click(function(){
		var output = parseInt($(this).parent().find(".regulator-output").val());
		$(this).parent().find(".regulator-output").val(output+1);
	});*/
    $('body').on('click' , ".regulator-minus" , function () {
        var output = parseInt($(this).parent().find(".regulator-output").val());
        if(output>0){
            $(this).parent().find(".regulator-output").val(output-1);
        }
    });

    $('body').on('click' , ".regulator-plus" , function () {
        var output = parseInt($(this).parent().find(".regulator-output").val());
        $(this).parent().find(".regulator-output").val(output+1);
    });
	$("#step1").change(function() {
		var orderItem = $("#step1 option:selected").attr("class");
		if(orderItem == "advertisement"){
			$(".order-bottom").addClass("d-none");
			$(".order-new").addClass("d-none");
			$(".order-advertisement").removeClass("d-none");
		}
		else{
			$(".order-bottom").removeClass("d-none");
			$(".order-new").removeClass("d-none");
			$(".order-advertisement").addClass("d-none");
		}
		$(".order-top__center ." +orderItem).removeClass("d-none").addClass("d-block");
		$(".order-top__right ." +orderItem).removeClass("d-none").addClass("d-block");
		$(".order-top__center--item").not("." +orderItem).addClass("d-none").removeClass("d-block");
		$(".order-top__right--item").not("." +orderItem).addClass("d-none").removeClass("d-block");
	});
	$(".order-trash").click(function(){
		$(this).addClass("d-none");
		$(this).parent().find(".order-ready__item").addClass("d-none");
	});
	$("#newServiceSelect").change(function() {
		$("#newService").modal('hide');
		$("#newOrder").modal('show');
	});
	$("#newServiceSelect").change(function() {
		var newServiceItem = $("#newServiceSelect option:selected").attr("class");
		$("#newOrderSelect option[value="+newServiceItem+"]").attr('selected', 'selected');
		$(".modal-service__body--items ." +newServiceItem).removeClass("d-none").addClass("d-block");
		$(".modal-service__body--item").not("." +newServiceItem).addClass("d-none").removeClass("d-block");
	});
	$("#newOrderSelect").change(function() {
		var newOrderItem = $("#newOrderSelect option:selected").attr("class");
		$(".modal-service__body--items ." +newOrderItem).removeClass("d-none").addClass("d-block");
		$(".modal-service__body--item").not("." +newOrderItem).addClass("d-none").removeClass("d-block");
	});
	if( window.matchMedia( '(max-width: 575px)' ).matches ){
		$("#header-search button").attr("type", "button");
		function changeAttr(){
			$("#header-search button").attr("type", "submit");
		}
		var body = document.getElementById('body');
		var except = document.getElementById('header-search');
		body.addEventListener("click", function () {
			$("#header-search").removeClass("header-search__mobile");
			$("#header-search button").attr("type", "button");
		}, false);
		except.addEventListener("click", function (ev) {
			$("#header-search").addClass("header-search__mobile");
			setTimeout(changeAttr, 200);
			$("#header-search input").focus();
			ev.stopPropagation();
		}, false);
	}
});
function openSecondaryMenu(){
   document.getElementById( "headerSecondaryMenu" ).style.width = "100%";
}
function closeSecondaryMenu(){
 document.getElementById( "headerSecondaryMenu" ).style.width = "0";
}
$('.carousel-pause').carousel({
	interval: false,
	wrap: false
});
$('#datetimepicker1').datetimepicker({
	format: 'LT',
	locale: 'az'
});
moment.locale('en-gb');
// moment.locale('ru');
// moment.locale('az');

// daterangepicker default state

$(".datetime-text").text(moment().format('D MMMM YYYY'));

//input values default start
$("#datetime-text").val(moment().format('D MMMM YYYY'));
//input values default end

$(".datetime").daterangepicker({
  orientation: 'left',
  expanded: true,
  single: true,
  locale: { applyButtonTitle: 'Apply', cancelButtonTitle: 'Cancel', endLabel: 'End', startLabel: 'Start' },
  callback: function (endDate) {
    $(this).find(".datetime-text").text(endDate.format('D MMMM YYYY'));
    //input values on change start
    $(this).find("#datetime-text").val(endDate.format('D MMMM YYYY'));
    //input values on change end
  }
});
/* ------------------------------- Valeh -------------------- */
$(document).ready(function() {
	$("#aniimated-thumbnials").lightGallery({
		thumbnail: true
	});

	$('.comments-modal__open').click(function () {
		$('.comments-modal').fadeIn('slow');
		$('.comments-modal').css('display' , 'flex');
	});

	$('.comments-modal__close').click(function () {
		$('.comments-modal').fadeOut('slow');
	});

	$(".comments-header__send").click(function (e) {
		$('html,body').animate({
			scrollTop: $("#main-send").offset().top
		}, 1000);
	});

	$('.main-slider__document').click(function () {
		$('.main-corporative__slider').hide();
		$('.main-corporative__modal').show();
	});
	$('.main-slider__close').click(function () {
		$('.main-corporative__slider').hide();
	});
	$('.main-corporative__right-read').click(function () {
		$('.main-corporative__slider').css("display" , "flex");
	});
	$('.main-document__close').click(function () {
		$('.main-corporative__modal').hide();
	});
	$('.main-clear__filter .filter-inner li').click(function () {
		$('.main-clear__filter .filter-inner li').removeClass('active');
		$(this).addClass('active');
		let id = $(this).attr('id');
		$('.main-clear__type').hide();
		$('.' + id).show();
	});
	$('.main-tabs__button').click(function () {
		$('.main-tabs__button').removeClass('active');
		$(this).addClass('active');
		var id = $(this).attr('id');
		$('.main-tabs__table').hide();
		$('.' + id).show();
	});
	$('.main-opinion__reply a').click(function (e) {
		$('.main-opinion__form').show();
		$('html , body').animate({
			scrollTop: $('#main-send__form').offset().top
		} , 1000);
	});
	// $('.header-language__dropdown--menu ul li').click(function () {
	// 	$('.header-dropdown__menu ul li').removeClass('active');
	// 	$(this).addClass('active');
	// 	var lang = $(this).attr('class').split(' ')[0];
	// 	var src = `img/${lang}.svg`;
	// 	$('.header-language img').attr('src' , src);
	// 	$('.header-language a').text(lang);
	// });
	// $(".tab-secondary").off("click").css("cursor", "default");
});
/* ------------------------------- End Valeh ---------------- */
