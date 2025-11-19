(function () { // Code preview function
	var $button = $("<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>").click(function () {
			var html = $(this).parent().html();
			html = cleanSource(html);
			$("#source-modal pre").text(html);
			$("#source-modal").modal();
		});

	$(".bs-component").hover(function () {
		$(this).append($button);
		$button.show();
	}, function () {
		$button.hide();
	});

	function cleanSource(html) {
		var lines = html.split(/\n/);

		lines.shift();
		lines.splice(-1, 1);

		var indentSize = lines[0].length - lines[0].trim().length,
		re = new RegExp(" {" + indentSize + "}");

		lines = lines.map(function (line) {
				if (line.match(re)) {
					line = line.substring(indentSize);
				}

				return line;
			});

		lines = lines.join("\n");

		return lines;
	}
})();
// START jQuery functions after page ready.
jQuery(document).ready(function ($) {
	$('.dropdown-menu').css('margin-top', '0'); // Workaround for accidental menu closing - Option 1
	//$('.dropdown-menu').css({ // Workaround for accidental menu closing - Option 2
	//	"margin-top" : "0",
	//	"border-top" : "0"
	//});
	// START jQuery Scroll Nav Plugin Initialization
	$(function () {
		$("#autohide_navbar").scrollNav({
			"bootstrap_mobile" : true
		});
	});
	// END jQuery Scroll Nav Plugin Initialization

	// START Tooltip And Popovers initialization
	// Create Tooltips adding this => data-toggle="tooltip" data-placement="left-or-right-or-top-or-bottom" title="" data-original-title="TOOLTIP TEXT"
	$('[data-toggle="tooltip"]').tooltip();
	// Create Popovers adding this => data-container="body" data-toggle="popover" data-original-title="Popover Title" data-placement="left-or-right-or-top-or-bottom" data-content="Popover content."
	//$('[data-toggle="popover"]').popover(); // Open popover on click
	$('[data-toggle="popover"]').popover({trigger: 'hover'}); // Open popover on hover
	// END Tooltip And Popovers initialization

	// START Dropdown classes open on hover
	//$('.dropdown-menu').css('margin-top', '0'); // Workaround for accidental menu closing
	$('.navbar .dropdown').hover(function () {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(50).fadeIn('fast');
	}, function () {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).fadeOut('fast', function () {
				na.removeClass('extra-nav-class')
			})
	});
	$('.dropdown-submenu').hover(function () {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(50).fadeIn('fast');
	}, function () {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).fadeOut('fast', function () {
				na.removeClass('extra-nav-class')
			})
	});
	$('.dropdown').hover(function () {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(50).fadeIn('fast');
	}, function () {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).fadeOut('fast', function () {
				na.removeClass('extra-nav-class')
			})
	});
	$('.btn-group').hover(function () {
		$(this).addClass('extra-nav-class').find('.dropdown-menu').first().stop(true, true).delay(50).fadeIn('fast');
	}, function () {
		var na = $(this)
			na.find('.dropdown-menu').first().stop(true, true).delay(50).fadeOut('fast', function () {
				na.removeClass('extra-nav-class')
			})
	});
	// END Dropdown classes open on hover
});

// START jQuery Scroll Nav Plugin
(function ($) {
	$.fn.scrollNav = function (opts) {
		//fix bootstrap bug when navbar is fixed positioned
		if (opts) {
			if (opts.bootstrap_mobile) {
				$(document.body).append("<style type='text/css'> @media(max-width: 767px) { .navbar-fixed-top, .navbar-fixed-bottom, .navbar-static-top { margin-right: 0; margin-left: 0;} }</style>");
			}
		}
		var
		window_scroll = $(window).scrollTop(),
		navbar = this,
		navbar_height = navbar.height(),
		scroll_last = window_scroll,
		navbar_visible = navbar_height;

		var resize_handler = function (event) {
			navbar_height = navbar.height();
		}
		$(window).resize(resize_handler);
		//bootstrap mobile compatibility
		this.find(".nav-collapse").on("shown", resize_handler);
		this.find(".nav-collapse").on("hidden", resize_handler);
		$(window).scroll(function (event) {
			//calculate how far the window was scrolled
			//scrolling up the page is a positive delta
			window_scroll = $(window).scrollTop()
				var
				scroll_delta = scroll_last - window_scroll,
			navbar_visible_new = navbar_visible + scroll_delta;
			if (scroll_delta < 0) {
				//scrolling down the page
				if (navbar_visible == navbar_height) {
					//navbar currently is totally visible, and has fixed positioning set
					//set to abs positioning so it begins to go out of frame
					navbar.css({
						"position" : "absolute",
						"top" : window_scroll + "px"
					});
				}
				//else:
				//navbar will be partially visible, let abs positioning move it
			} else if (scroll_delta > 0) {
				if (navbar_visible <= 0) {
					//navbar was not visible, set abs positioning right above this
					navbar.css({
						"position" : "absolute",
						"top" : (window_scroll - navbar_height) + "px"
					});
				}
				//scrolling up the page
				if (navbar_visible_new >= navbar_height) {
					//navbar will be 100% visible
					navbar.css({
						"position" : "fixed",
						"top" : "0px"
					});
				}
			}
			//recalculate the amount the navbar is visible
			navbar_visible = Math.min(Math.max(navbar_visible_new, 0), navbar_height);
			scroll_last = window_scroll;
		});
	}
})(jQuery);
// END jQuery Scroll Nav Plugin