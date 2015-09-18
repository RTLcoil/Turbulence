var acfMapStyle = [

    {

        "featureType": "administrative",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "off"

            }

        ]

    },

    {

        "featureType": "administrative",

        "elementType": "labels.text.fill",

        "stylers": [

            {

                "color": "#444444"

            }

        ]

    },

    {

        "featureType": "landscape",

        "elementType": "all",

        "stylers": [

            {

                "hue": "#ff0000"

            },

            {

                "saturation": "-100"

            },

            {

                "lightness": "28"

            }

        ]

    },

    {

        "featureType": "poi",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "off"

            }

        ]

    },

    {

        "featureType": "road",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "off"

            },

            {

                "saturation": -100

            },

            {

                "lightness": 45

            }

        ]

    },

    {

        "featureType": "road.highway",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "off"

            }

        ]

    },

    {

        "featureType": "road.arterial",

        "elementType": "labels.icon",

        "stylers": [

            {

                "visibility": "off"

            }

        ]

    },

    {

        "featureType": "transit",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "off"

            }

        ]

    },

    {

        "featureType": "water",

        "elementType": "all",

        "stylers": [

            {

                "visibility": "on"

            },

            {

                "color": "#ffffff"

            }

        ]

    }

];

var mapArtistPopupOffsets = {

	x: 0,

	y: 0

};

(function($) {

	$.fn.sliderCSS = function(params) {

		var opts = $.extend({

			timeout: 5000,

			speed  : 600,
			pager  : null,
			before : function(){},

			after  : function(){}

		}, params);



		return this.each(function() {

			var $el = $(this);



			if($el.data("sliderCSS")) {

				return;

			}



			var timer,

				$elems = $el.children();



			$el.data("sliderCSS", {

				startAuto: function() {

					if(!timer) {

						timer = setInterval(function() {

							move();

						}, opts.timeout);

					}

				},



				stopAuto: function() {

					if(timer) {

						clearInterval(timer);

						timer = null;

					}

				},



				goTo: function(index) {

					move(index);

				}

			});

			function init() {
				$elems.each(function() {
					var $img = $("img", this);

					if($img && $img.length) {
						if($(".slider-medium__item-img", this).length) {
							$(".slider-medium__item-img", this).css("background-image", "url(" + $img.attr("src") + ")");

							if($img.attr("data-origin")) {
								$(".slider-medium__item-img", this).attr("data-origin", $img.attr("data-origin"));
							}
						} else if ($(".main-gallery__content-inner", this).length) {
							$(".main-gallery__content-inner", this).css("background-image", "url(" + $img.attr("src") + ")");
						}

						$img.addClass("slide-complete");
					}
				});

				if(!$elems.filter(".current").length) {
					$elems.eq(0).addClass("current");
				}

				if($elems.length > 1) {
					$el.data("sliderCSS").startAuto();
				} else {
					if(opts.pager) {
						opts.pager.remove();
					}
				}
			}



			function move(index) {

				var cur  = $elems.filter(".current"),

					next = index!=undefined ? $elems.eq(index)

								 : cur.next().length ? cur.next() : $elems.first();



				opts.before(cur, next);



				$el.addClass("transition_true");

				cur.addClass("slide-out");

				next.addClass("slide-in");



				setTimeout(function() {

					$el.removeClass("transition_true");

					cur.removeClass("slide-out current");

					next.removeClass("slide-in").addClass("current");



					opts.after(cur, next);

				}, opts.speed);

			}



			init();

		});

	};

}(jQuery));



var tmpl = {

	popupIcon:      '<div class="i-item__popup" style="box-shadow: 0 0 0 4px {color}; color: {color}"><a href="{url}" class="i-item__popup-img"><span class="i-item__popup-frame"></span><img src="{img}" alt="" class="i-item__popup-work"><span class="label-category">{category}</span></a><div class="i-item__popup-title">{title}<span>{author}</span></div><div class="i-item__popup-tags">{tags}</div></div>',

	popupIconTitle: '<div class="i-item__popup" style="box-shadow: 0 0 0 4px {color}; color: {color}"><a href="{url}" class="i-item__popup-img"><span class="i-item__popup-frame"></span><img src="{img}" alt="" class="i-item__popup-work"><span class="label-category">{category}</span></a><div class="i-item__popup-title">{title}<span>{author}</span></div><div class="i-item__popup-tags">{tags}</div></div>',

	popupArtist:    '<div class="i-item__popup i-item__popup_person" style="box-shadow: 0 0 0 4px {color}; color: {color}"><div class="i-item__popup_map"><div class="acf-map"><div class="marker" data-lat="{mapLat}" data-lng="{mapLng}"></div></div></div><a href="{artistUrl}" class="i-item__popup-name"><img src="{artist}" alt="" class="i-item__popup-artist"><span class="i-item__popup-artist-name">{name}</span><span class="i-item__popup-place">{place}</span></a><div class="i-item__popup-works">{works}</div></div>'

};

var lazyLoad = {
	show: function() {
		var _this = this,
			$elems = _this.p.elements.not(".lazy_ready").filter(":visible"),
			count = 0;

		$elems.each(function(i) {
			var p = this.getBoundingClientRect(),
				h = $(window).height();

			if(p.top > 0 && p.bottom < h) {
				(function(element, index) {
					$(element).addClass("lazy_ready");
					count += 1;
					var img = new Image();
					img.onload = function() {
						element.src = this.src;

						setTimeout(function() {
							if($(element).hasClass("slide-medium-img")) {
								$(element).closest(".slider-medium__item-img").css({
									backgroundImage: 'url(' + $(element).attr("data-origin") + ')',
									opacity: 0
								}).animate({
									opacity: 1
								});
							} else {
								$(element).animate({
									opacity: 1
								}, 250);
							}
						}, count * 100);
					};
					img.src = $(element).attr(_this.p.attr);
				}(this, i));
			}
		});
	},

	check: function(delay) {
		var _this = this;

		setTimeout(function() {
			_this.show();
		}, delay || 0);
	},

	init: function(params) {
		var _this = this;
		_this.p = params;

		$(window).on(this.p.eventName, function() {
			_this.show();
		});
	}
};

(function($) {
	$(function() {
		(function() { // preloader
			$(window).load(function() {
				$(".loading-overlay").addClass("preloader-hide");
			});
		})();

		if($(".main-gallery").length) {

			(function() {

				var pager = $("#main-gallery__pager a"),

					$desc = $(".main-gallery__titles li");

                $(".main-gallery iframe").each(function() {
                    var src = this.src,
                        re = /\?/;

                    $(this).attr("data-src", src);

                    if(re.test(src)) {
                        $(this).attr("data-src-autoplay", src + "&autoplay=1");
                    } else {
                        $(this).attr("data-src-autoplay", src + "?autoplay=1");
                    }
                });

				$(".main-gallery__content-slider").sliderCSS({
					speed: 600,
					pager: pager,
					timeout: 10000,

					before: function(cur, next) {
						pager.eq(next.index()).addClass("active").siblings(".active").removeClass("active");
						$desc.eq(next.index()).addClass("active").siblings(".active").removeClass("active");
					},

                    after: function(prev, cur) {
                        var $iframeCur = cur.find("iframe"),
                            $iframeCurAutoplay = cur.find(".main-gallery__content-link_video-inner").data("autoplay"),
                            $iframePrev = prev.find("iframe"),
                            $iframePrevAutoplay = prev.find(".main-gallery__content-link_video-inner").data("autoplay");

                        if($iframeCur.length && $iframeCurAutoplay) {
                            try {
                                $iframeCur.attr("src", $iframeCur.attr("data-src-autoplay"));
                            } catch (e) {}
                        }

                        if($iframePrev.length && $iframePrevAutoplay) {
                            try {
                                $iframePrev.attr("src", $iframePrev.attr("data-src"));
                            } catch (e) {}
                        }
                    }
				});



				var data = $(".main-gallery__content-slider").data("sliderCSS");



				pager.on({

					"click": function(e) {

						e.preventDefault();



						var index = $(this).data("slide-index");



						data.goTo(index);

					},



					"mouseenter": function() {

						data.stopAuto();

					},



					"mouseleave": function() {

						data.startAuto();

					}

				})



			} () );

		}



		if($(".slider-medium__wrap").length) {

			(function() {

				var pager = $(".slider-medium__pager a"),

					$desc = $(".slider-medium__desc li");



				$(".slider-medium__wrap").sliderCSS({

					speed: 400,

					timeout: 6000,

					before: function(cur, next) {

						var index = next.index();



						pager.eq(index).addClass("active").siblings(".active").removeClass("active");

						$desc.eq(index).addClass("active").siblings(".active").removeClass("active");

					}

				});



				var data = $(".slider-medium__wrap").data("sliderCSS");



				pager.on({

					"click": function(e) {

						e.preventDefault();



						var index = $(this).data("slide-index");

						data.goTo(index);

					},



					"mouseenter": function() {

						data.stopAuto();

					},



					"mouseleave": function() {

						data.startAuto();

					}

				});

			} () );

		}


		lazyLoad.init({
			elements : $("img.thumbnail, .slide-medium-img"),
			attr     : "data-origin",
			eventName: "scroll"
		});


		if($(".show-site").length) {

			$(".show-site").on("click", function(e) {

				e.preventDefault();



				$("html:not(:animated), body:not(:animated)").animate({

					scrollTop: $(window).height()

				});

			});

		}


    // Make the site unveil on first scroll:

    var lastScrollTop = $(window).scrollTop();
    $(window).scroll(function(event){
      if($(".main-gallery").length){      //if the main gallery exists
       var st = $(this).scrollTop();
       if (st > lastScrollTop){
           // downscroll code
           if (lastScrollTop == 0) {
             $('html, body').animate({
               scrollTop: $(".main-gallery").height()+1
             }, {
               duration: 500,
               easing: "easeOutQuart"
             });
           }
       } else {
          // upscroll code
       }
       lastScrollTop = st;
     }
    });

		/*if($(".header_front").length) {

			$(window).on("scroll", function() {

				var st = $(window).scrollTop();



				$("body")[st >= $(window).height()+20 ? "addClass" : "removeClass"]("header-fixed");

				if($(".link-logo").is(":empty") && $(".nav-toggle-btn").is(":empty")) {

					$(".nav-toggle-btn").html(svgImage);

					$(".nav-toggle .link-logo").html(svgImage);

				}

			});

		}*/



		/*if($(".header_inner").length) {

			$(".nav-toggle-btn").html(svgImage);

			$(".nav-toggle .link-logo").html(svgImage);

		}*/

		if(!$(".main-gallery").length) {
			$(".header_fixed").addClass("header_filling");
		} else {
			$(window).on("scroll", function() {
				var st = $(window).scrollTop();

				$(".header_fixed")[st >= $(window).height()-$(".main-gallery__footer").height() ? "addClass" : "removeClass"]("header_filling");
			});
		}



		if($("[data-toggle]").length) {
			$("[data-toggle]").on("click", function(e) {
				var $this = $(this),
					$elem = $($this.data("toggle")),
					dir   = $this.data("toggle-dir");

				if(dir) {
					var flag = !!($elem.width() > 80),
						width = flag ? 0 : ($elem.children(":first").outerWidth() + $elem.children(":first").position().left);

					if(!flag) {
						$elem.css("visibility", "visible");

						if($(e.target).hasClass("nav-toggle-btn")) {
							$(".nav-toggle").addClass("nav-toggle_dark");
						}
					} else {
						if($(e.target).hasClass("nav-toggle-btn")) {
							$(".nav-toggle").removeClass("nav-toggle_dark");
						}
					}

					$elem.stop().animate({
						width: width
					}, 250, 'linear', function() {
						if(flag) {
							$elem.css("visibility", "hidden");
						}
					});
				} else {
					$elem.length && $elem.slideToggle(0);
				}
			});
		}



		if($("[data-toggle-block]").length) {

			$("[data-toggle-block]").on("change", function() {

				if($(this).is(":checked")) {

					var $elem = $($(this).data("toggle-block"));



					$elem.length && ($elem.show().siblings(":visible").hide());

				}

			});

		}



		$(window).load(function() {

			(function() {

				var $container         = $(".isotope"),
					$inputsTypeFilter  = $("[name=filter-type]"),
					$inputSearch       = $(".search-block__field input[type='search']"),
					$labelsField       = $(".search-block__filter-labels"),
					$menuFilter        = $(".search-block__filter-menu"),
					$period            = $(".search-block__period"),
					$labels            = $(".filter-label"),
					$labelsInput       = $("[name=filter-labels]"),
					$sortByYearsUp     = $(".search-block__period-years .search-block__period-left"),
					$sortByYearsDown   = $(".search-block__period-years .search-block__period-right"),
					$sortByLettersUp   = $(".search-block__period-letters .search-block__period-left"),
					$sortByLettersDown = $(".search-block__period-letters .search-block__period-right"),
					$sortByTitleUp     = $(".search-block__period-years-titles .search-block__period-left"),
					$sortByTitleDown   = $(".search-block__period-years-titles .search-block__period-right"),
					$itemIcon          = $(".i-item_icon"),
					$itemIconTitle     = $(".i-item_icon_title"),
					$itemArtist        = $(".i-item_artist"),


					isSmallScreen      = !!(('matchMedia' in window && window.matchMedia("(max-width: 767px)").matches) || $(window).width() < 767 || $('.search-block__filter [class*="search-block__filter-item"] span:first').is(":hidden")),


					columnWidth        = isSmallScreen ? 35 : 45;


				var $typeIcons = $(".type-icon"),
					typeIconsLength = $typeIcons.length;

				$container.isotope({ // init isotope
					layoutMode: 'fitRows',

					masonry: {
						columnWidth: columnWidth
					},

					getSortData: {
						yearUp    : '[data-sort-up] parseInt',
						yearDown  : '[data-sort-down] parseInt',
						letterUp  : '[data-sort-up-letter] parseInt',
						letterDown: '[data-sort-down-letter] parseInt',
						titleUp   : '[data-sort-up-title] parseInt',
						titleDown : '[data-sort-down-title] parseInt'
					}

				});

				$sortByYearsDown.on("click", function() { // sort by years
					changeActive($(this));
					reverseValue($(this), !1);

					$container.isotope({
						sortBy: 'yearDown'
					});

					lazyLoad.check(600);
				});

				$sortByYearsUp.on("click", function() { // sort by years
					changeActive($(this));
					reverseValue($(this), !0);

					$container.isotope({
						sortBy: 'yearUp'
					});

					lazyLoad.check(600);
				});

				$sortByLettersDown.on("click", function() { // sort by letters
					changeActive($(this));
					reverseValue($(this), !1);

					$container.isotope({
						sortBy: 'letterDown'
					});

					lazyLoad.check(600);
				});

				$sortByLettersUp.on("click", function() { // sort by letters
					changeActive($(this));
					reverseValue($(this), !0);

					$container.isotope({
						sortBy: 'letterUp'
					});

					lazyLoad.check(600);
				});

				$sortByTitleDown.on("click", function() { // sort by title
					changeActive($(this));
					reverseValue($(this), !1);

					$container.isotope({
						sortBy: 'titleDown'
					});

					lazyLoad.check(600);
				});

				$sortByTitleUp.on("click", function() { // sort by title
					changeActive($(this));
					reverseValue($(this), !0);

					$container.isotope({
						sortBy: 'titleUp'
					});

					lazyLoad.check(600);
				});

				/////////////////////////////////////////////////

				var labelTmpl = '<span class="search-block__label" data-value="{value}">\
									{value} <i class="search-block__label-remove">&times;</i>\
								</span>';

				$labels.on("mousedown", function(e) {
					e.preventDefault();

					var _this = $(this);

					$("input", _this).prop("checked", !$("input", _this).is(":checked"));
					_this[$("input", _this).is(":checked") ? "addClass" : "removeClass"]("chosen");

					if($("input", _this).is(":checked")) {
						$(labelTmpl.replace(/\{value\}/g, $("input", _this)[0].value)).insertBefore(".search-block__field");
					} else {
						$(".search-block__label:contains(" + $("input", _this)[0].value + ")").remove();
					}

					searchMatch(true);

					if(!getChosen().length) {
						$inputSearch.focus();
						$period.show();
					} else {
						$period.hide();
					}
				}).on("click", function(e) {
					e.preventDefault();
				});

				$("body").on("click", ".i-item__popup-tags a", function(e) {
					e.preventDefault();

					var txt = $(this).text();

					$(".filter-label").each(function() {
						if($(this).hasClass("hidden")) {
							return;
						}

						if(txt == $('input', this).val()) {
							$(this).trigger("mousedown");
						}
					});
				});

				$("body").on("mousedown", ".search-block__label-remove", function() { // remove label
					var $label = $(this).closest(".search-block__label"),
						value = $.trim($label.data("value")),
						$chosen = getChosen();

					$chosen.each(function() {
						if(this.value == value) {
							$(this).prop("checked", false).closest(".chosen").removeClass("chosen");
						}
					});

					$(this).remove();
					$label.remove();

					setTimeout(function() {
						$inputSearch.focus();
						searchMatch(true);
					}, 0);
				});

				$("body").on("mousedown", ".search-block__label", function(e) { // select-unselect label
					if(e.target == this) {
						$(this).toggleClass("active");
					}

					setTimeout(function() {
						$inputSearch.focus();
					}, 0);
				});

				$inputSearch.on({
					focus: function() {
						$menuFilter.hide();
						$labelsField.show();

						searchMatch(this.value);
					},

					blur: function() {
						setTimeout(function() {
							if(!getChosen().length) {
								$menuFilter.show();
								$labelsField.hide();
								$period.show();
							}
						}, 0);
					},

					input: function(e) {
						if(!this.value) {
							$menuFilter.hide();
							$labelsField.show();
							$period.hide();

							removeLabel(this.value);

							searchMatch(this.value);
						} else {
							$menuFilter.show();
							$labelsField.hide();
							$period.show();

							removeLabel(this.value);

							searchMatch(this.value);
						}
					},

					keydown: function(e) {
						var $this = $(this);

						setTimeout(function() {
							if(e.which == 8 && !$this.val()) {
								if($(".search-block__label.active").length) {
									$(".search-block__label.active").each(function(i) {
										$(".search-block__label-remove", this).trigger("mousedown");
									});
								} else if ($(".search-block__label").length) {
									$(".search-block__label:last .search-block__label-remove").trigger("mousedown");
								}
							}
						}, 0);
					}
				}).autocomplete({
					close: function(e, ui) {
						searchMatch($inputSearch.val());
					}
				});

				smallFilter();
				fillAutocomplete();

				$inputsTypeFilter.on("change", function() {
					smallFilter();
					fillAutocomplete();

					if($inputSearch.val()) {
						searchMatch($inputSearch.val());
					}

					lazyLoad.check(600);
				});

				function getType() {
					return $inputsTypeFilter.filter(":checked").val();
				}

				function getChosen(a) {
					return $labelsInput.filter(":checked");
				}

				function smallFilter() {
					var filterValue = getType();

					$container.isotope({
						filter: filterValue
					});
				}

				function removeLabel(value) {
					var values = $inputSearch.val(),
						chosen = getChosen();

					if(~values.indexOf(",")) {
						values = values.split(",");
					} else {
						values = [].concat(values);
					}

					if(chosen.length && value) {
						for(var i = 0, j = values.length; i < j; i += 1) {
							chosen.each(function(k) {
								if(~this.value.indexOf($.trim(values[i])) && this.value != $.trim(values[i])) {
									$(this).prop("checked", false).closest(".chosen").removeClass("chosen");
								}
							});
						}
					} else if (!value && chosen.length) {
						chosen.each(function(k) {
							$(this).prop("checked", false).closest(".chosen").removeClass("chosen");
						});
					}

					searchMatch();
				}


				function searchMatch(onlyLabel) {
					var className   = getType().slice(1),
						words       = [],
						matchesElem = [];

					if($(".search-block__label").length) {
						$(".search-block__label").each(function() {
							words.push($(this).data("value"));
						});
					}

					if($inputSearch.val()) {
						words.push($inputSearch.val());
					}

					$container.isotope({
						filter: function(e, elem) {
							if(!$(elem).hasClass(className)) {
								return !1;
							}

							var flag = true;

							$.each(words, function(index, value) {
								/*if(!isNaN(+$(elem).data('search'))) {
									return true;
								}*/

								var re = new RegExp(value, "gi"),
									str = onlyLabel ? $(elem).data('labels') : ($(elem).data('labels') + "," + $(elem).data('search'));

								if($(elem).data('category')) {
									str += $(elem).data('category');
								}

								if(!re.test(str)) {
									flag = false;
								}
							});

							flag && matchesElem.push(elem);

							return flag;
						}
					});

					matchesElem.length && toggleLabels(matchesElem);
				}

				function toggleLabels($elems) {
					if($elems.length < 1) {
						$(".filter-label.hidden").removeClass("hidden");
					} else {
						$(".filter-label").addClass("hidden");
					}

					$(".filter-label").each(function() {
						var $this = $(this),
							value = $("input", this).val(),
							re;

						if(!value) {
							return;
						}

						re = new RegExp(value, "i");

						$.each($elems, function(i) {
							var str = $(this).data("labels");

							if($this.hasClass("cat-label")) {
								str += " " + $(this).data("category");
							}

							if(re.test(str)) {
								$this.hasClass("hidden") && $this.removeClass("hidden");
							}
						});
					});
				}

				//////////////////////

				function searchTags() {

return;


					var $chosen = getChosen(),
						values = [];

					if($chosen.length) {
						$chosen.each(function() {
							values.push(this.value.toLowerCase());
						});

						$container.isotope({
							filter: function(e, elem) {
								var txt = ("" + $(elem).data('labels')).toLowerCase().split(",");

								for(var i = 0, j = txt.length; i < j; i += 1) {
									if($.inArray(txt[i], values) !== -1) {
										return true;
									}
								}

								return false;
							}
						});
					} else {
						smallFilter();
					}
				}

				function fillAutocomplete() {
					var sourse = [];

					$(getType()).each(function() {
						var data = $(this).data("search");

						if(!data) {
							return;
						}

						sourse.push(data);
					});

					$inputSearch.autocomplete("option", "source", sourse);
				}

				function changeActive($this) {
					if($this.hasClass("active")) {
						//return;
					}

					$this.addClass("active").siblings(".active").removeClass("active");
				}

				function reverseValue($elem, bol) {
					$elem.parent()[bol ? "removeClass" : "addClass"]("revert");
				}

				////////////////////////////////////

				function createIconPopup($root) {
					try {
						var data = $root.data(),
								html = tmpl.popupIcon,
								tags, links = '';

						html = html
							.replace('{img}', data.img)
							.replace('{url}', data.url)
							.replace('{title}', data.title)
							.replace('{category}', data.category)
							.replace('{catslug}', data.catslug)
							.replace('{author}', data.author);

						tags = data.labels.split(",");
						tagsUrl = data.labelsUrl.split(",");
						links = '';

						if("customColor" in data && data.customColor) {
							var re = new RegExp("{color}", "gi");

							while(re.test(html)) {
								html = html.replace("{color}", data.customColor);
							}

							html = html.replace("i-item__popup", "i-item__popup i-item__popup_custom-color");
						}

						for(var i = 0, j = tags.length; i < j; i += 1) {
							links += ('<a href="' + (""+tagsUrl[i]) + '">' + tags[i] + '</a>');
						}

						html = html.replace('{tags}', links);

						return html;
					} catch(e) {}
				}

				function createIconTitlePopup($root) {
					var data = $root.data(),
							html = tmpl.popupIconTitle,
							tags, links = '';

					html = html
						.replace('{img}', data.img)
						.replace('{url}', data.url)
						.replace('{title}', data.title)
						.replace('{category}', data.category)
						.replace('{author}', data.author);

					tags = data.labels.split(",");
					tagsUrl = data.labelsUrl.split(",");
					links = '';

					if("customColor" in data && data.customColor) {
						var re = new RegExp("{color}", "gi");
						while(re.test(html)) {
							html = html.replace("{color}", data.customColor);
						}

						html = html.replace("i-item__popup", "i-item__popup i-item__popup_custom-color");
					}

					for(var i = 0, j = tags.length; i < j; i += 1) {
						links += ('<a href="' + (""+tagsUrl[i]) + '">' + tags[i] + '</a>');
					}

					html = html.replace('{tags}', links);

					return html;
				}

				function createArtistPopup($root) {
					var data = $root.data(),
						html = tmpl.popupArtist,
						works, worksResult = '',
						name = data.name.split("?"),
						mapLng = data.mapLng,
						mapLat = data.mapLat;

					if(mapLng) {
						//mapLng = mapLng + mapArtistPopupOffsets.x;
					}

					if(mapLat) {
						//mapLat = mapLat + mapArtistPopupOffsets.y;
					}

					html = html
						.replace('{map}', data.mapSrc)
						.replace('{artist}', data.artist)
						.replace('{place}', data.place)
						.replace('{name}', name[0])
						.replace('{artistUrl}', name[1])
						.replace('{mapLng}', mapLng)
						.replace('{mapLat}', mapLat);

					if("customColor" in data && data.customColor) {
						var re = new RegExp("{color}", "gi");

						while(re.test(html)) {
							html = html.replace("{color}", data.customColor);
						}

						html = html.replace("i-item__popup", "i-item__popup i-item__popup_custom-color");
					}

					works = data.works.split(",");

					for(var i = 0, j = works.length; i < j; i += 1) {
						var str = works[i].split("?");
						worksResult += ('<a href="' + str[1] + '"><img src="' + str[0] + '" alt=""></a>');
					}

					html = html.replace('{works}', worksResult);
					return html;
				}

				var $elemsIcons = $("> img, > a", $itemIcon).add(".artist-details__relevant-list .i-item > img").add(".artist-details__relevant-list .i-item > a");

				$elemsIcons.each(function(i) {
					var timerIcon;

					$(this).on("mouseenter.desktopPopupOpen", function(e) {
						var $opened  = $(".opened-popup"),
							$closest = $(this).closest(".i-item"),
							$popup   = $(".i-item__popup", $closest);

						if(timerIcon) {
							clearTimeout(timerIcon);
							timerIcon = null;
						}

						if($closest.offset().left + 300 > $(window).width()) {
							$closest.addClass("side-left").removeClass("side-right");
						} else {
							$closest.addClass("side-right").removeClass("side-left");
						}

						if($popup.length) {
							$popup.show();
							$closest.addClass("opened-popup");
						} else {
							$closest.append(createIconPopup($closest)).addClass("opened-popup");
						}
					}).closest(".i-item").on("mouseleave.desktopPopupOpen", function() {
						var _this = $(this);

						if(timerIcon) {
							clearTimeout(timerIcon);
							timerIcon = null;
						}

						timerIcon = setTimeout(function() {
							_this.removeClass("opened-popup").find(".i-item__popup").hide();
						}, 150);
					}).on("mouseenter.desktopPopupOpen", function() {
						if(timerIcon) {
							clearTimeout(timerIcon);
							timerIcon = null;
						}
					});
				});

				$("> img, > a", $itemIconTitle).each(function(i) {
					var timerIconTitle;

					$(this).on("mouseenter.desktopPopupOpen", function(e) {
						var $opened  = $(".opened-popup"),
							$closest = $(this).closest(".i-item"),
							$popup   = $(".i-item__popup", $closest);

						if(timerIconTitle) {
							clearTimeout(timerIconTitle);
							timerIconTitle = null;
						}

						if($closest.offset().left + 300 > $(window).width()) {
							$closest.addClass("side-left").removeClass("side-right");
						} else {
							$closest.addClass("side-right").removeClass("side-left");
						}

						if($popup.length) {
							$popup.show();
							$closest.addClass("opened-popup");
						} else {
							$closest.append(createIconPopup($closest)).addClass("opened-popup");
						}
					}).closest(".i-item").on("mouseleave.desktopPopupOpen", function() {
						var _this = $(this);

						if(timerIconTitle) {
							clearTimeout(timerIconTitle);
							timerIconTitle = null;
						}

						timerIconTitle = setTimeout(function() {
							_this.removeClass("opened-popup").find(".i-item__popup").hide();
						}, 150);
					}).on("mouseenter.desktopPopupOpen", function() {
						if(timerIconTitle) {
							clearTimeout(timerIconTitle);
							timerIconTitle = null;
						}
					});
				});

				$("> img, > a", $itemArtist).each(function() {
					var timerItemArtist;

					$(this).on("mouseenter.desktopPopupOpen", function(e) {
						if(e.target == this) {
							var $opened = $(".opened-popup"),
								$closest = $(this).closest(".i-item"),
								offsetLeft = $(this).offset().left;

							if($opened.length && !$closest.hasClass("opened-popup")) {
								$opened.removeClass("opened-popup").find(".i-item__popup").hide();
							}

							if($closest.hasClass("opened-popup")) {
								$closest.removeClass("opened-popup").find(".i-item__popup").hide();
								return;
							}

							var $popup = $(".i-item__popup", $closest);

							if($popup.length) {
								$popup.show();
								$closest.addClass("opened-popup");
							} else {
								$closest.append(createArtistPopup($closest)).addClass("opened-popup");
							}

							if(offsetLeft - 75 < 0) {
								$(".i-item__popup", $closest).css("left", -offsetLeft+1);
							} else {
								$(".i-item__popup", $closest).css("left", -75);
							}

							if($(".marker", $closest).length && !$(".marker", $closest).hasClass("marker-ready")) { // marker-ready
								render_map($('.acf-map', $closest));
								$(".marker", $closest).addClass("marker-ready");
							}
						}
					}).closest(".i-item").on("mouseleave.desktopPopupOpen", function() {
						var _this = $(this);

						if(timerItemArtist) {
							clearTimeout(timerItemArtist);
							timerItemArtist = null;
						}

						timerItemArtist = setTimeout(function() {
							_this.removeClass("opened-popup").find(".i-item__popup").hide();
						}, 0);
					}).on("mouseenter.desktopPopupOpen", function() {
						if(timerItemArtist) {
							clearTimeout(timerItemArtist);
							timerItemArtist = null;
						}
					});
				});

				function createMobileGallery(clsName) {
					var $result = "";

					if(!$("#overlay").length) {

						$('<div />', {
							id: 'overlay'
						}).appendTo("body");
					}

					$result += '<div class="popup-gallery" id="popup-icons"><div class="popup-gallery__inner"><div class="popup-gallery__slider">';

					$.each($("." + clsName), function() {
						if(clsName == "i-item_icon") {
							$result += '<div class="popup-gallery__slider-item">' + createIconPopup($(this)) + '</div>';
						} else {
							$result += '<div class="popup-gallery__slider-item"><div class="i-item__popup">' + ($(".i-item__popup", this).html()) + '</div></div>';
						}
					});

					$result += '</div></div><div class="popup-gallery__close"></div></div>';

					$("body").append($result);
				}

				function createMobileGalleryIconTitle() {
					var $result = "";

					if(!$("#overlay").length) {

						$('<div />', {
							id: 'overlay'
						}).appendTo("body");
					}

					$result += '<div class="popup-gallery" id="popup-icons-titles"><div class="popup-gallery__inner"><div class="popup-gallery__slider">';

					$.each($(".i-item_icon"), function() {
						$result += '<div class="popup-gallery__slider-item">' + createIconPopup($(this)) + '</div>';
					});

					$result += '</div></div><div class="popup-gallery__close"></div></div>';

					$("body").append($result);
				}

				function createMobileGalleryArtist() {
					var $result = "";

					if(!$("#overlay").length) {
						$('<div />', {
							id: 'overlay'
						}).appendTo("body");
					}

					$result += '<div class="popup-gallery" id="popup-artist"><div class="popup-gallery__inner"><div class="popup-gallery__slider">';

					$.each($(".i-item_artist"), function() {
						$result += '<div class="popup-gallery__slider-item">' + createArtistPopup($(this)) + '</div>';
					});

					$result += '</div></div><div class="popup-gallery__close"></div></div>';

					$("body").append($result);
				}



				(function() {
					if(isSmallScreen) {
						var $imgIcon       = $("> img, > a", $itemIcon),
							$imgIconTitles = $("> img, > a", $itemIconTitle),
							$imgArtist     = $("> img", $itemArtist),
							$aIcon         = $("> a", $itemIcon),
							$aIconTitles   = $("> a", $itemIconTitle),
							$aArtist       = $("> a", $itemArtist),
							sliderIcon, sliderIconTitle, sliderArtist;

						$aIcon.add($aArtist).add($aIconTitles).off(".desktopPopupOpen").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$(this).siblings("img").click();
						});

						if(!$imgIcon.length) {
							$imgIcon = $(".artist-details__relevant .i-item");
						}

						$imgIcon.off(".desktopPopupOpen").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$("#overlay").fadeIn();
							$("#popup-icons").fadeIn();

							var index = $imgIcon.index(this);

							if($("#popup-icons .popup-gallery__slider").children().length > 1) {
								if(!$("#popup-icons").hasClass("slider-ready")) {
									$("#popup-icons").addClass("slider-ready");

									sliderIcon = $("#popup-icons .popup-gallery__slider").bxSlider({
										pager: false,
										controls: false,
										slideWidth: 230,
										startSlide: index
									});
								} else {
									sliderIcon.goToSlide(index);
								}
							} else {

							}
						});

						$imgIconTitles.off(".desktopPopupOpen").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$("#overlay").fadeIn();
							$("#popup-icons-titles").fadeIn();

							var index = $imgIconTitles.index(this);

							if(!$("#popup-icons-titles").hasClass("slider-ready")) {
								$("#popup-icons-titles").addClass("slider-ready");

								sliderIconTitle = $("#popup-icons-titles .popup-gallery__slider").bxSlider({
									pager: false,
									controls: false,
									slideWidth: 230,
									startSlide: index
								});
							} else {
								sliderIconTitle.goToSlide(index);
							}
						});

						$imgArtist.off(".desktopPopupOpen").closest(".i-item_artist").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$("#overlay").fadeIn();
							$("#popup-artist").fadeIn();

							var index = $imgArtist.index($("> img", this)),
								$elem;

							if(!$("#popup-artist").hasClass("slider-ready")) {
								$("#popup-artist").addClass("slider-ready");

								sliderArtist = $("#popup-artist .popup-gallery__slider").bxSlider({
									pager: false,
									controls: false,
									slideWidth: 230,
									startSlide: index,

									onSlideBefore: function($slideElement, oldIndex, newIndex) {
										if($(".marker", $slideElement).length && !$(".marker", $slideElement).hasClass("marker-ready")) {
											render_map($('.acf-map', $slideElement));
											$(".marker", $slideElement).addClass("marker-ready");
										}
									}
								});

								$elem = $("#popup-artist .popup-gallery__slider").children(":not(.bx-clone)").eq(sliderArtist.getCurrentSlide());

								if($(".marker", $elem).length && !$(".marker", $elem).hasClass("marker-ready")) {
									render_map($('.acf-map', $elem));
									$(".marker", $elem).addClass("marker-ready");
								}

							} else {
								sliderArtist.goToSlide(index);

								$elem = $("#popup-artist .popup-gallery__slider").children(":not(.bx-clone)").eq(sliderArtist.getCurrentSlide());

								if($(".marker", $elem).length && !$(".marker", $elem).hasClass("marker-ready")) {
									render_map($('.acf-map', $elem));
									$(".marker", $elem).addClass("marker-ready");
								}
							}
						});

						createMobileGallery($(".i-item_icon").length ? "i-item_icon" : "i-item");
						createMobileGalleryIconTitle();
						createMobileGalleryArtist();

						$("#overlay").click(function() {
							$("#overlay").fadeOut();
							$(".popup-gallery:visible").fadeOut();
						});

						$(".popup-gallery__close, .popup-gallery").on("click", function(e) {
							if(e.target == this) {
								$("#overlay").fadeOut();
								$(".popup-gallery:visible").fadeOut();
							}
						});
					}
				}());
			}());
		});
	});

	    /*

     *  render_map

     *

     *  This function will render a Google Map onto the selected jQuery element

     *

     *  @type    function

     *  @date    8/11/2013

     *  @since    4.3.0

     *

     *  @param    $el (jQuery element)

     *  @return    n/a

     */



    function render_map( $el ) {



        // var

        var $markers = $el.find('.marker');



        // vars

        var args = {

            zoom        : 5,

            styles:      acfMapStyle,

//            center        : new google.maps.LatLng(0, 0),

            center: new google.maps.LatLng(parseFloat($el.find('.marker:first').attr('data-lat')) + 1.8, $el.find('.marker:first').attr('data-lng')),

//            center: new google.maps.LatLng(50.871768297397445, -113.93932679662703),

            mapTypeId    : google.maps.MapTypeId.ROADMAP,

            disableDoubleClickZoom: true,

            draggable: false,

            overviewMapControl: false,

            panControl: false,

            scrollwheel: false,

            streetViewControl: false,

            zoomControl: false,

            mapTypeControl: false



        };



//        // create map

        var map = new google.maps.Map( $el[0], args);

//

//        // add a markers reference

//        map.markers = [];

//

//        // add markers

//        $markers.each(function(){

//

//            add_marker( $(this), map );

//

//        });

//

//        // center map

//        center_map( map );



    }



    /*

     *  add_marker

     *

     *  This function will add a marker to the selected Google Map

     *

     *  @type    function

     *  @date    8/11/2013

     *  @since    4.3.0

     *

     *  @param    $marker (jQuery element)

     *  @param    map (Google Map object)

     *  @return    n/a

     */



    function add_marker( $marker, map ) {



        // var

        var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );



        // create marker

        var marker = new google.maps.Marker({

            position    : latlng,

            map            : map

        });



        // add to array

        map.markers.push( marker );



        // if marker contains HTML, add it to an infoWindow

        if( $marker.html() )

        {

            // create info window

            var infowindow = new google.maps.InfoWindow({

                content        : $marker.html()

            });



            // show info window when marker is clicked

            google.maps.event.addListener(marker, 'click', function() {



                infowindow.open( map, marker );



            });

        }



    }



    /*

     *  center_map

     *

     *  This function will center the map, showing all markers attached to this map

     *

     *  @type    function

     *  @date    8/11/2013

     *  @since    4.3.0

     *

     *  @param    map (Google Map object)

     *  @return    n/a

     */



    function center_map( map ) {



        // vars

        var bounds = new google.maps.LatLngBounds();



        // loop through all markers and create bounds

        $.each( map.markers, function( i, marker ){



            var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );



            bounds.extend( latlng );



        });



        // only 1 marker?

        if( map.markers.length == 1 )

        {

            // set center of map

            map.setCenter( bounds.getCenter() );

            map.setZoom( 16 );

        }

        else

        {

            // fit to bounds

            map.fitBounds( bounds );

        }



    }



    /*

     *  document ready

     *

     *  This function will render each map when the document is ready (page has loaded)

     *

     *  @type    function

     *  @date    8/11/2013

     *  @since    5.0.0

     *

     *  @param    n/a

     *  @return    n/a

     */



    $(document).ready(function(){

        if($('.acf-map').length) {

            $('.acf-map').each(function(){



                render_map( $(this) );



            });

        }

    });

}(jQuery));
