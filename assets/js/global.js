;
(function ($) {

    /*
    let responsiveSliderSettings = {
        rows: 0,
        slidesToShow: 2,
        dots: true,
    };
    let $responsiveSlider = $('.selector');
     */

    // Scripts which runs after DOM load
    var scrollOut;
    $(document).ready(function () {

        // Init LazyLoad
        var lazyLoadInstance = new LazyLoad({
            elements_selector: 'img[data-lazy-src],.pre-lazyload,[data-pre-lazyload],video:not([src]):not([data-lazy-src]),video[data-lazy-src]',
            data_src: "lazy-src",
            data_srcset: "lazy-srcset",
            data_sizes: "lazy-sizes",
            skip_invisible: false,
            class_loading: "lazyloading",
            class_loaded: "lazyloaded",
        });
        // Add tracking on adding any new nodes to body to update lazyload for the new images (AJAX for example)
        window.addEventListener('LazyLoad::Initialized', function (e) {
            // Get the instance and puts it in the lazyLoadInstance variable
            if (window.MutationObserver) {
                var observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        mutation.addedNodes.forEach(function (node) {
                            if (typeof node.getElementsByTagName !== 'function') {
                                return;
                            }
                            imgs = node.getElementsByTagName('img');
                            if (0 === imgs.length) {
                                return;
                            }
                            lazyLoadInstance.update();
                        });
                    });
                });
                var b = document.getElementsByTagName("body")[0];
                var config = {childList: true, subtree: true};
                observer.observe(b, config);
            }
        }, false);

        // Load all images in slider after init
        $(document).on("init", ".slick-slider", function (e, slick) {
            lazyLoadInstance.loadAll(slick.$slider[0].getElementsByTagName('img'));
        });

        /*
        // responsiveSliderSettings - Settings for slider on responsive. Create this variable in the top of this file before $(document).ready()
        reinitSlickOnResize($responsiveSlider, responsiveSliderSettings, 641)
         */

        // Detect element appearance in viewport
        // scrollOut = ScrollOut({
        //     offset: function () {
        //         return window.innerHeight - 200;
        //     },
        //     targets: '.acf-map,[data-scroll]',
        //     once: true,
        //     // onShown: function( element ) {
        //     // 	if ( $( element ).is( '.ease-order' ) ) {
        //     // 		$( element ).find( '.ease-order__item' ).each( function( i ) {
        //     // 			var $this = $( this );
        //     // 			$( this ).attr( 'data-scroll', '' );
        //     // 			window.setTimeout( function() {
        //     // 				$this.attr( 'data-scroll', 'in' );
        //     // 			}, 300 * i );
        //     // 		} );
        //     // 	}
        //     // 	if ( $( element ).is( '.acf-map' ) ) {
        //     // 		render_map( $( element ) );
        //     // 	}
        //     // }
        // });


        // Init parallax
        if (typeof $.fn.jarallax !== 'undefined') {
            $('.jarallax').jarallax({
                speed: 0.5,
            });

            $('.jarallax-inline').jarallax({
                speed: 0.5,
                keepImg: true,
                onInit: function () {
                    lazyLoadInstance.update();
                }
            });
        }

        //Remove placeholder on click
        $('input, textarea').each(function () {
            removeInputPlaceholderOnFocus(this);
        });

        //Make elements equal height
        if (typeof $.fn.matchHeight !== 'undefined') {
            $('.matchHeight').matchHeight();
        }

        // Add fancybox to images
        $('.gallery-item').find('a[href$="jpg"], a[href$="png"], a[href$="gif"]').attr('rel', 'gallery').attr('data-fancybox', 'gallery');
        $('a[rel*="album"], .fancybox, a[href$="jpg"], a[href$="png"], a[href$="gif"]').fancybox({});

        /**
         * Scroll to Gravity Form confirmation message after form submit
         */
        $(document).on('gform_confirmation_loaded', function (event, formId) {
            var $target = $('#gform_confirmation_wrapper_' + formId);
            smoothScrollTo($target);
        });

        // Init Jquery UI select
        $("select").not("#billing_state, #shipping_state, #billing_country, #shipping_country, [class*='woocommerce'], #product_cat, #rating").each(function () {
            initSelect2(this);
        });

        $(document).on('gform_post_render', function (event, form_id, current_page) {
            const $form = $("#gform_" + form_id)
            $form.find("select").each(function () {
                initSelect2(this);
            });

            $form.find("input, textarea").each(function () {
                removeInputPlaceholderOnFocus(this);
            });
        });

        $(document).on('click', '.s-qty-dec,.s-qty-inc', function () {
            var $numberInput = $(this).closest('.quantity').find('input'),
                action = $(this).is('.s-qty-inc') ? 'stepUp' : 'stepDown';
            $numberInput[0][action]();
            $numberInput.trigger('change');
        });

        /**
         * Update lazyload images and reinit select on cart/checkout update
         */
        $(document).on('updated_wc_div', function () {
            lazyLoadInstance.loadAll();
            $('body').find('div.woocommerce').find('select').each(function () {
                initSelect2(this);
            });
        });

        /**
         * Hide gravity forms required field message on data input
         */
        $('body').on('change keyup', '.gfield input, .gfield textarea, .gfield select', function () {
            var $field = $(this).closest('.gfield');
            if ($field.hasClass('gfield_error') && $(this).val().length) {
                $field.find('.validation_message').hide();
            } else if ($field.hasClass('gfield_error') && !$(this).val().length) {
                $field.find('.validation_message').show();
            }
        });

        /**
         * Add `is-active` class to menu-icon button on Responsive menu toggle
         * And remove it on breakpoint change
         */
        $(window).on('toggled.zf.responsiveToggle', function () {
            $('.menu-icon').toggleClass('is-active');
        }).on('changed.zf.mediaquery', function (e, value) {
            $('.menu-icon').removeClass('is-active');
        });

        /**
         * Close responsive menu on orientation change
         */
        $(window).on('orientationchange', function () {
            setTimeout(function () {
                if ($('.menu-icon').hasClass('is-active') && window.innerWidth < 641) {
                    $('[data-responsive-toggle="main-menu"]').foundation('toggleMenu')
                }
            }, 200);
        });

        resizeVideo();

        // Share post popup
        $('.js-share-link').click(function (e) {
            e.preventDefault();
            var wpWidth = $(window).width(), wpHeight = $(window).height();
            window.open($(this).attr('href'), 'Share', "top=" + (wpHeight - 400) / 2 + ",left=" + (wpWidth - 600) / 2 + ",width=600,height=400");
        });


    });


    // Scripts which runs after all elements load

    $(window).on('load', function () {

        if (typeof scrollOut !== "undefined") {
            scrollOut.update();
        }

        //jQuery code goes here
        if ($('.preloader').length) {
            $('.preloader').addClass('preloader--hidden');
        }

    });

    // Scripts which runs at window resize

    let resizeVideoCallback = debounce(resizeVideo, 200);
    // let resizeSliderCallback = debounce( reinitSlickOnResize, 200 );
    $(window).on('resize', function () {

        //jQuery code goes here
        resizeVideoCallback();

        /*
        resizeSliderCallback( $responsiveSlider, responsiveSliderSettings, 641 );
        */


    });

    // Scripts which runs on scrolling

    $(window).on('scroll', function () {

        //jQuery code goes here

    });

    /*
     *  This function will render a Google Map onto the selected jQuery element
     */

    function render_map($el) {
        // var
        var $markers = $el.find('.marker');
        // var styles = Here should be styles for Google Maps from https://snazzymaps.com/explore ; // Uncomment for map styling

        // vars
        var args = {
            zoom: 14,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false,
            styles: [
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e9e9e9"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 29
                        },
                        {
                            "weight": 0.2
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 18
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dedede"
                        },
                        {
                            "lightness": 21
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#333333"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        },
                        {
                            "lightness": 19
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                }
            ] // Uncomment for map styling
        };

        // create map
        var map = new google.maps.Map($el[0], args);

        // add a markers reference
        map.markers = [];

        // add markers
        $markers.each(function () {
            add_marker($(this), map);
        });

        // center map
        center_map(map);
    }

    /*
     *  This function will add a marker to the selected Google Map
     */

    var infowindow;

    function add_marker($marker, map) {
        // var
        var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));

        // create marker
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            //icon: $marker.data('marker-icon') //uncomment if you use custom marker
        });

        // add to array
        map.markers.push(marker);

        // if marker contains HTML, add it to an infoWindow
        if ($.trim($marker.html())) {
            // create info window
            infowindow = new google.maps.InfoWindow();

            // show info window when marker is clicked
            google.maps.event.addListener(marker, 'click', function () {
                // Close previously opened infowindow, fill with new content and open it
                infowindow.close();
                infowindow.setContent($marker.html());
                infowindow.open(map, marker);
            });
        }
    }

    /*
    *  This function will center the map, showing all markers attached to this map
    */

    function center_map(map) {
        // vars
        var bounds = new google.maps.LatLngBounds();

        // loop through all markers and create bounds
        $.each(map.markers, function (i, marker) {
            var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
            bounds.extend(latlng);
        });

        // only 1 marker?
        if (map.markers.length == 1) {
            // set center of map
            map.setCenter(bounds.getCenter());
        } else {
            // fit to bounds
            map.fitBounds(bounds);
        }
    }

    /**
     * Helper functions
     */

    function debounce(callback, time) {
        var timeout;

        return function () {
            var context = this;
            var args = arguments;
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function () {
                timeout = null;
                callback.apply(context, args);
            }, time);
        }
    }

    function handleFirstTab(e) {
        var key = e.key || e.keyCode;
        if (key === 'Tab' || key === '9') {
            $('body').removeClass('no-outline');

            window.removeEventListener('keydown', handleFirstTab);
            window.addEventListener('mousedown', handleMouseDownOnce);
        }
    }

    function handleMouseDownOnce() {
        $('body').addClass('no-outline');

        window.removeEventListener('mousedown', handleMouseDownOnce);
        window.addEventListener('keydown', handleFirstTab);
    }

    window.addEventListener('keydown', handleFirstTab);

    // Fit slide video background to video holder
    function resizeVideo() {
        var $holder = $(".video-holder");
        $holder.each(function () {
            var $that = $(this);
            var ratio = $that.data("ratio") ? $that.data("ratio") : "16:9",
                width = parseFloat(ratio.split(":")[0]),
                height = parseFloat(ratio.split(":")[1]);
            $that.find(".video-holder__media").each(function () {
                if ($that.width() / width > $that.height() / height) {
                    $(this).css({"width": "100%", "height": "auto"});
                } else {
                    $(this).css({"width": $that.height() * width / height, "height": "100%"});
                }
            });
        });
    }

    // Init Select2 plugin
    function initSelect2(elem) {
        var $field = $(elem);
        var $gfield = $field.closest(".gfield");
        var $countryBox = $field.closest('.ginput_address_country,.gfield_time_ampm');
        var args = {}
        if ($countryBox.length) {
            args.dropdownParent = $countryBox;
        } else if ($gfield.length) {
            args.dropdownParent = $gfield;
        }

        $field.select2(args);
    }

    function removeInputPlaceholderOnFocus(el) {
        $(el).data("holder", $(el).attr("placeholder"));

        $(el).on("focusin", function () {
            $(el).attr("placeholder", "");
        });

        $(el).on("focusout", function () {
            $(el).attr("placeholder", $(el).data("holder"));
        });
    }

    /**
     * Init slick slider on smaller screens, And destroy it on desktop
     */
    function reinitSlickOnResize($slider, settings, breakpoint) {
        if (window.innerWidth >= breakpoint) {
            if ($slider.hasClass("slick-initialized")) {
                $slider.slick("unslick");
            }
        } else {
            if (!$slider.hasClass("slick-initialized")) {
                $slider.slick(settings);
            }
        }
    }

    /**
     * Smooth scroll to target
     */
    function smoothScrollTo($target, offset) {
        offset = typeof offset == "undefined" ? 0 : offset;
        $("html, body").animate({
            scrollTop: $target.offset().top - 50 - offset,
        }, 500);
        $target.focus();
        if ($target.is(":focus")) { // Checking if the target was focused
            return false;
        } else {
            $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        var items = document.querySelectorAll(".broadmoor-family__item");

        function checkVisible() {
            var windowTop = window.scrollY;
            var windowBottom = windowTop + window.innerHeight;

            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                var position = item.getBoundingClientRect();
                var itemTop = position.top + windowTop;
                var itemBottom = position.bottom + windowTop;

                var offset = 100;

                if ((itemTop >= windowTop + offset && itemTop <= windowBottom) ||
                    (itemBottom >= windowTop + offset && itemBottom <= windowBottom)) {
                    item.classList.add("animate__animated");
                }
            }
        }

        window.addEventListener("load", checkVisible);
        window.addEventListener("scroll", checkVisible);
    });
    // $(document).ready(function(){
    // 	$('.bringing-slider').slick({
    // 		dots: true,
    // 		infinite: true,
    // 		speed: 500,
    // 		slidesToShow: 1,
    // 		centerMode: true,
    // 		variableWidth: true,
    // 		autoplay: true,
    // 		autoplaySpeed: 1000,
    // 	});
    // });

    document.addEventListener("DOMContentLoaded", function () {
        var videos = document.querySelectorAll("video");
        videos.forEach(function (video) {
            var playButton = video.nextElementSibling;
            if(playButton) {


                playButton.style.display = "block";

                playButton.addEventListener("click", toggleVideo);

                function toggleVideo() {
                    if (video.paused) {
                        video.play();
                        playButton.style.display = "none";
                        video.style.opacity = "1";
                    } else {
                        video.pause();
                        playButton.style.display = "block";
                        video.style.opacity = "0.22";
                    }
                }

                video.addEventListener("click", function() {
                    if (!video.paused) {
                        toggleVideo();
                    }
                });

                video.addEventListener("pause", function () {
                    playButton.style.display = "block";
                    video.style.opacity = "0.22";
                });

                video.addEventListener("play", function () {
                    playButton.style.display = "none";
                    video.style.opacity = "1";
                });
            }

        });
    });













    $(document).ready(function () {
        if ($(window).width() < 1024) {
            $('.bringing-comfort__right-content--container-images').slick({
                infinite: true,
                slidesToShow: 2,
                slidesToScroll: 1,
                arrows: false,
                dots: true,
                autoplay: true,
                autoplaySpeed: 5000,
                responsive: [
                    {
                        breakpoint: 641,
                        settings: {
                            slidesToShow: 1
                        },
                    },
                ]
            });


        }
        if ($(window).width() >= 1024) {
            var leftImage = $('.bringing-comfort__left-image');
            var centerImage = $('.bringing-comfort__center-image');
            var rightImage = $('.bringing-comfort__right-image');
            var content = $('.bringing-comfort__right-content--container-text');
            var n = 0;
            if (typeof galleryArray !== 'undefined' && leftImage.length && centerImage.length && rightImage.length && content.length) {
                if(galleryArray.length > 3){
                    // content.html(galleryArray[n]['content']);
                    leftImage.html('<img src="' + galleryArray[n]['url'] + '">');
                    if (n >= galleryArray.length - 1) {
                        n = 0;
                    } else {
                        n++
                    }
                    centerImage.html('<img src="' + galleryArray[n]['url'] + '">');
                    if (n >= galleryArray.length - 1) {
                        n = 0;
                    } else {
                        n++
                    }
                    rightImage.html('<img src="' + galleryArray[n]['url'] + '">');
                    rightImage.removeClass('fadeOut').addClass('fadeIn');
                    if (n <= 0) {
                        n = galleryArray.length - 1;
                    } else {
                        n--;
                    }

                    function swapImages() {
                        leftImage.removeClass('fadeIn')
                        leftImage.addClass('fadeOut');
                        // content.removeClass('fadeIn')
                        // content.addClass('fadeOut');
                        setTimeout(function () {
                            content.html(galleryArray[n]['content']);
                            leftImage.html('<img src="' + galleryArray[n]['url'] + '">');
                            if (n >= galleryArray.length - 1) {
                                n = 0;
                            } else {
                                n++
                            }
                            centerImage.removeClass('fadeIn')
                            centerImage.addClass('fadeOut');
                            leftImage.removeClass('fadeOut').addClass('fadeIn');
                            content.removeClass('fadeOut').addClass('fadeIn');
                        }, 200);
                        setTimeout(function () {
                            setTimeout(function () {
                                centerImage.html('<img src="' + galleryArray[n]['url'] + '">');
                                if (n >= galleryArray.length - 1) {
                                    n = 0;
                                } else {
                                    n++
                                }
                                rightImage.removeClass('fadeIn')
                                rightImage.addClass('fadeOut');
                                centerImage.removeClass('fadeOut').addClass('fadeIn');
                            }, 200);
                        }, 400);

                        setTimeout(function () {
                            setTimeout(function () {
                                rightImage.html('<img src="' + galleryArray[n]['url'] + '">');
                                rightImage.removeClass('fadeOut').addClass('fadeIn');
                                if (n <= 0) {
                                    n = galleryArray.length - 1;
                                } else {
                                    n--;
                                }
                            }, 200);
                        }, 600);
                    }

                    setInterval(swapImages, 6000);
                }
                else {
                    leftImage.html('<img src="' + galleryArray[n]['url'] + '">');
                    n++;
                    centerImage.html('<img src="' + galleryArray[n]['url'] + '">');
                    n++;
                    rightImage.html('<img src="' + galleryArray[n]['url'] + '">');
                }

            }
        }
    });


    function setupVideoPlayer() {
        setTimeout(function() {
        var playerContainer = document.getElementById('playerContainer');
        if(playerContainer){
            var youtubeId = playerContainer.getAttribute('data-youtube-id');
            var autoplay = playerContainer.getAttribute('data-autoplay');

            var player = new YT.Player('playerContainer', {
                height: '100%',
                width: '100%',
                videoId: youtubeId,
                playerVars: {
                    'autoplay': autoplay === 'autoplay-on' ? 1 : 0,
                    'mute': 1, // Mute the video
                    'controls': 0, // Hide controls
                    'fs': 0, // Hide fullscreen button
                    'modestbranding': 1, // Hide YouTube logo
                    'rel': 0, // Hide related videos
                    'showinfo': 1, // Hide video title and uploader information
                    'loop': 1, // Loop the video
                    'playlist': youtubeId // Necessary for looping
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });

            function onPlayerReady(event) {
                if (autoplay !== 'autoplay-on') {
                    document.querySelector('.play-button').addEventListener('click', function() {
                        player.playVideo();
                    });
                }
            }

            function onPlayerStateChange(event) {
                if (autoplay !== 'autoplay-on') {
                    if (event.data === YT.PlayerState.PAUSED) {
                        document.querySelector('.play-button').style.display = 'block';
                    } else {
                        setTimeout(function() {
                            document.querySelector('.play-button').style.display = 'none';
                        }, 10);
                    }
                }
            }
        }
        }, 1000);
    }





    $(document).ready(function () {
        setupVideoPlayer();
        $('.is-active-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            adaptiveHeight: true,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 641,
                    settings: {
                        slidesToShow: 1
                    },
                },
            ]
        });
        if($('.is-active-slider')){
            $('.is-active-slider').on('setPosition', function(event, slick) {
                var trackHeight = $(this).find('.slick-track').height();
                $(this).find('.slick-slide').height(trackHeight);
            });
        }
        $('.we-love__gallery').slick({
            infinite: true,
            slidesToShow: 5,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4
                    },
                },
                {
                    breakpoint: 821,
                    settings: {
                        slidesToShow: 3
                    },
                },
                {
                    breakpoint: 641,
                    settings: {
                        slidesToShow: 2
                    },
                },
                {
                    breakpoint: 361,
                    settings: {
                        slidesToShow: 1
                    },
                },
            ]
        });
    });
    $('.tabs-title a').click(function (event) {
        var targetSlug = $(this).attr('href').replace('#', '');
        $('.slider-' + targetSlug).slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            adaptiveHeight: true,
            arrows: false,
            dots: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2
                    },
                },
                {
                    breakpoint: 641,
                    settings: {
                        slidesToShow: 1
                    },
                },
            ]
        });
        setTimeout( function (){
            $('.slider-' + targetSlug).on('setPosition', function(event, slick) {
                var trackHeight = $(this).find('.slick-track').height();
                $(this).find('.slick-slide').height(trackHeight);
            });
        }, 100)
    });

    $('.your-team__item i').click(function () {
        var postId = $(this).data('id');
        // console.log(postId);
    });


    $(document).ready(function () {
        $('.dropdown').on('mouseleave', function() {
            $('.dropdown-menu').fadeOut(300);
        });


        $('.play-button').click(function () {
            $('.overlay').css('display', 'none')
        });

        function setNavContainerHeight() {
            var windowWidth = $(window).width();
            if (windowWidth <= 640) {
                var headerHeight = $('.header').outerHeight();
                $('.nav-container').css('top', headerHeight + 'px');
                $('.single-hero-image').css('top', headerHeight + 'px');
                $('.services-hero-image').css('top', headerHeight + 30 + 'px');
            } else {
                $('.nav-container').css('height', '');
            }
        }

        setNavContainerHeight();

        $(window).resize(function () {
            setNavContainerHeight();
        });


        let teamId = '';
        $('.your-team__item i').click(function () {
            $('#teamPopup').removeClass('popup-active')
            $('#teamPopup').html('')

            let teamId = $(this).data('id');
            let clickHeight = $(this).offset().top;

            $('#teamPopup').css('top', clickHeight + 'px');
            ajaxTeam(teamId);

            setTimeout(function () {
                $('#teamPopup').addClass('popup-active');
            }, 600);
            $('html, body').animate({
                scrollTop: clickHeight - 10
            }, 600);
        });

        function ajaxTeam(teamId) {
            $.ajax({
                type: 'GET',
                url: ajax.url,
                data: {
                    action: 'teamPopup',
                    teamId: teamId,
                },
                success: function (response) {
                    $('#teamPopup').html(response)
                    // console.log(response)
                }
            })
        }

        $('#teamPopup').on('click', '.x-mark', function () {
            $('#teamPopup').removeClass('popup-active')
        });
        $(document).on('click', function (event) {
            if (!$(event.target).closest('#teamPopup').length) {
                $('#teamPopup').removeClass('popup-active');
            }
        });
    });
    $(document).ready(function () {
        $(".template").hide(1);
        $('.learn-more').click(function (event) {
            event.preventDefault();
            if ($(this).hasClass("active")) {
                $(".services-archive-posts .active").removeClass("active");
                $(".template").hide("slow");
            } else {
                $(".services-archive-posts .active").removeClass("active");
                $(".template").hide(500);
                var dataId = $(this).data('id');
                $('.' + dataId).addClass('active');
                $('.' + dataId).show()
                $(this).addClass('active')
                $('.plus[data-id="' + dataId + '"]').addClass('active');

                setTimeout(function () {
                    var templateActive = $('.active .grid-container');
                    if (templateActive.length > 0) {
                        var scrollTop = templateActive.offset().top;
                        // console.log(scrollTop)
                        $('html, body').animate({
                            scrollTop: scrollTop - 100
                        }, 500);
                    }
                }, 500);
                setTimeout(function () {
                    $('.slider-' + dataId).slick({
                        infinite: true,
                        slidesToScroll: 1,
                        centerMode: true,
                        slidesToShow: 3,
                        cssEase: 'linear',
                        variableWidth: true,
                        responsive: [
                            {
                                breakpoint: 641,
                                settings: {
                                    slidesToShow: 1
                                },
                            },
                        ]
                    });
                    var playButtons = document.querySelectorAll('.play-button');

                }, 501)
            }
        });
        $('.plus').click(function (event) {
            event.preventDefault();
            if ($(this).hasClass("active")) {
                $(".services-archive-posts .active").removeClass("active");
                $(".template").hide(500);
            } else {
                $(".services-archive-posts .active").removeClass("active");
                $(".template").hide("slow");
                var dataId = $(this).data('id');
                $('.' + dataId).addClass('active');
                $('.' + dataId).show()
                $(this).addClass('active');
                $('.learn-more[data-id="' + dataId + '"]').addClass('active');

                setTimeout(function () {

                    var templateActive = $('.active .grid-container');
                    if (templateActive.length > 0) {
                        var scrollTop = templateActive.offset().top;
                        // console.log(scrollTop)
                        $('html, body').animate({
                            scrollTop: scrollTop - 100
                        }, 500);
                    }
                }, 500);
                setTimeout(function () {
                    $('.slider-' + dataId).slick({
                        infinite: true,
                        slidesToScroll: 1,
                        centerMode: true,
                        slidesToShow: 3,
                        cssEase: 'linear',
                        variableWidth: true,
                        responsive: [
                            {
                                breakpoint: 641,
                                settings: {
                                    slidesToShow: 1
                                },
                            },
                        ]
                    });
                }, 501)
            }
        });

        $(document).on('click', '.posts__new-post', function () {
            var link = $(this).find('.posts__new-post__link').attr('href');
            if (link) {
                window.location.href = link;
            }
        });
        $(document).on('click', '.posts__post', function () {
            var link = $(this).find('.posts__post__link').attr('href');
            if (link) {
                window.location.href = link;
            }
        });

        $('.posts__load-more a').on('click', function (event) {
            event.preventDefault();
            $.ajax({
                type: 'GET',
                url: ajax.url,
                data: {
                    action: 'load_more',
                },
                success: function (response) {
                    $('.posts .grid-container').html(response);
                },
            });
        });

    });
    $(document).ready(function () {
        if ($('.acf-map').length) {
            render_map($('.acf-map'));
        }

        function animateElementsOnScroll() {
            $('.animated-element').each(function () {
                var offsetTop = $(this).offset().top;
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();
                if (offsetTop < scrollTop + windowHeight) {
                    $(this).addClass('visible');
                }
            });
        }

        $(window).scroll(function () {
            animateElementsOnScroll();
        });

        animateElementsOnScroll();
    });
    // $(document).ready(function() {
    //     var hash = window.location.hash;
    //     if (hash) {
    //         $('html, body').animate({
    //             scrollTop: $(hash).offset().top
    //         }, 2000);
    //         console.log('true')
    //     }
    // });
    $(document).ready(function() {
        $('.general-dental__content--list li a').on('click', function(event) {
            var href = $(this).attr('href');
            var anchor = href.split('#')[1];
            if (anchor) {
                event.preventDefault();
                localStorage.setItem('scrollAnchor', anchor);
                href = href.split('#')[0];
            }

                window.location.href = href;

        });
        $('.cosmetic-dentistry__content--list li a').on('click', function(event) {
            var href = $(this).attr('href');
            var anchor = href.split('#')[1];
            if (anchor) {
                event.preventDefault();
                localStorage.setItem('scrollAnchor', anchor);
                href = href.split('#')[0];
            }

            window.location.href = href;

        });
        $('.preventative-dentistry__content--list li a').on('click', function(event) {
            var href = $(this).attr('href');
            var anchor = href.split('#')[1];
            if (anchor) {
                event.preventDefault();
                localStorage.setItem('scrollAnchor', anchor);
                href = href.split('#')[0];
            }

            window.location.href = href;

        });
        var anchor = localStorage.getItem('scrollAnchor');
        if (anchor) {
            console.log(anchor)
            setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('#' + anchor).offset().top
            }, 1500, function() {
                localStorage.removeItem('scrollAnchor');
            });
            }, 200);
        }
    });

    $(document).ready(function() {
        $('.dropdown').on('click', function(event) {
            var dropdownMenu = $(this).find('.dropdown-menu');
            $('.dropdown-menu').not(dropdownMenu).removeClass('active');
            dropdownMenu.toggleClass('active');
            event.stopPropagation();
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('active');
            }
        });
    });

}(jQuery));
