(function($) {
	$.fn.sliderCSS = function(params) {
		var opts = $.extend({
			timeout: 5000,
			speed  : 600,
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
				if(!$elems.filter(".current").length) {
					$elems.eq(0).addClass("current");
				}
				
				$el.data("sliderCSS").startAuto();
			}
			
			function move(index) {
				var cur  = $elems.filter(".current"),
					next = index ? $elems.eq(index) 
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
	popupIcon: '<div class="i-item__popup" style="box-shadow: 0 0 0 4px {color}; color: {color}"><div class="i-item__popup-frame"></div><a href="{url}" class="i-item__popup-img"><img src="{img}" alt="" class="i-item__popup-work"></a><div class="i-item__popup-title" style="color: {color}">{title}<span style="color: {color}">{author}</span></div><div class="i-item__popup-tags" style="color: {color}">{tags}</div></div>',

	popupIconTitle: '<div class="i-item__popup" style="box-shadow: 0 0 0 4px {color}"><div class="i-item__popup-frame"></div><a href="{url}" class="i-item__popup-img"><img src="{img}" alt="" class="i-item__popup-work"></a><div class="i-item__popup-title" style="color: {color}">{title}<span style="color: {color}">{author}</span></div><div class="i-item__popup-tags" style="color: {color}">{tags}</div></div>',
	
	popupArtist: '<div class="i-item__popup i-item__popup_person" style="box-shadow: 0 0 0 4px {color}; color: {color}"><div class="i-item__popup_map"><div class="acf-map"><div class="marker" data-lat="{mapLat}" data-lng="{mapLng}"></div></div></div><img src="{artist}" alt="" class="i-item__popup-artist"><a href="{artistUrl}" class="i-item__popup-name" style="color: {color}">{name}</a><div class="i-item__popup-place" style="color: {color}">{place}</div><div class="i-item__popup-works">{works}</div></div>'
};

(function($) {
	$(function() {
		if($(".main-gallery").length) {
			(function() {
				var pager = $("#main-gallery__pager a"),
					$desc = $(".main-gallery__titles li");
				
				$(".main-gallery__content-slider").sliderCSS({
					speed: 600,
					timeout: 10000,
					before: function(cur, next) {
						var index = next.index();
						
						pager.eq(index).addClass("active").siblings(".active").removeClass("active");
						$desc.eq(index).addClass("active").siblings(".active").removeClass("active");
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
		
		if($(".show-site").length) {
			$(".show-site").on("click", function(e) {
				e.preventDefault();
				
				$("html:not(:animated), body:not(:animated)").animate({
					scrollTop: $(window).height()
				});
			});
		}
		
		if($(".header_front").length) {
			$(window).on("scroll", function() {
				var st = $(window).scrollTop();
				
				//if('matchMedia' in window && window.matchMedia('(min-width: 768px)').matches) {
					$("body")[st >= $(window).height() ? "addClass" : "removeClass"]("header-fixed");
				//}
			});
		}
		
		if($("[data-toggle]").length) {
			$("[data-toggle]").on("click", function() {
				var $this = $(this),
					$elem = $($this.data("toggle")),
					dir   = $this.data("toggle-dir");

				if(dir) {
					var flag = !!($elem.width() > 80),
						width = flag ? 0 : ($elem.children(":first").outerWidth() + $elem.children(":first").position().left);

					if(!flag) {
						$elem.css("visibility", "visible");
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
				
					isSmallScreen      = !!$('.search-block__filter [class*="search-block__filter-item"] span:first').is(":hidden"),
				
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
				});
				
				$sortByYearsUp.on("click", function() { // sort by years
					changeActive($(this));
					reverseValue($(this), !0);
					
					$container.isotope({
						sortBy: 'yearUp'
					});
				});
				
				$sortByLettersDown.on("click", function() { // sort by letters
					changeActive($(this));
					reverseValue($(this), !1);
					
					$container.isotope({
						sortBy: 'letterDown'
					});
				});
				
				$sortByLettersUp.on("click", function() { // sort by letters
					changeActive($(this));
					reverseValue($(this), !0);
					
					$container.isotope({
						sortBy: 'letterUp'
					});
				});
				
				$sortByTitleDown.on("click", function() { // sort by title
					changeActive($(this));
					reverseValue($(this), !1);
					
					$container.isotope({
						sortBy: 'titleDown'
					});
				});
				
				$sortByTitleUp.on("click", function() { // sort by title
					changeActive($(this));
					reverseValue($(this), !0);
					
					$container.isotope({
						sortBy: 'titleUp'
					});
				});
				
				$labels.on("mousedown", function(e) {
					e.preventDefault();
					
					var _this = $(this);
					
					$("input", _this).prop("checked", !$("input", _this).is(":checked"));
					_this[$("input", _this).is(":checked") ? "addClass" : "removeClass"]("chosen");
					
					searchTags();
					
					if(!getChosen().length) {
						$inputSearch.focus();
						$period.show();
					} else {
						$period.hide();
					}
				}).on("click", function(e) {
					e.preventDefault();
				});
				
				$inputSearch.on({
					focus: function() {
						if(!this.value) {
							$menuFilter.hide();
							$labelsField.show();
							searchTags();
						} else {
							searchMatch(this.value);
						}
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
					
					input: function() {
						if(!this.value) {
							$menuFilter.hide();
							$labelsField.show();
							$period.hide();
							
							searchTags();
						} else {
							$menuFilter.show();
							$labelsField.hide();
							$period.show();
							
							searchMatch(this.value);
						}
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
				
				function searchMatch(text) {
					var className = getType().slice(1);
					
					$container.isotope({
						filter: function(e, elem) {
							if(!$(elem).hasClass(className)) {
								return !1;
							}
							
							return !!(("" + $(elem).data('search')).toLowerCase().indexOf(text.toLowerCase()) !== -1);
						}
					});
				}
				
				function searchTags() {
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
					var data = $root.data(),
							html = tmpl.popupIcon,
							tags, links = '';
						
					html = html
						.replace('{img}', data.img)
						.replace('{url}', data.url)
						.replace('{title}', data.title)
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
				
				function createIconTitlePopup($root) {
					var data = $root.data(),
							html = tmpl.popupIconTitle,
							tags, links = '';
						
					html = html
						.replace('{img}', data.img)
						.replace('{url}', data.url)
						.replace('{title}', data.title)
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
						name = data.name.split("?");
						
					html = html
						.replace('{map}', data.mapSrc)
						.replace('{artist}', data.artist)
						.replace('{place}', data.place)
						.replace('{name}', name[0])
						.replace('{artistUrl}', name[1])
						.replace('{mapLng}', data.mapLng)
						.replace('{mapLat}', data.mapLat);
					
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
				
				$("> img, > a", $itemIcon).each(function(i) {
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
				
				function createMobileGallery() {
					var $result = "";
					
					if(!$("#overlay").length) {
						$('<div />', {
							id: 'overlay'
						}).appendTo("body");
					}
					
					$result += '<div class="popup-gallery" id="popup-icons"><div class="popup-gallery__inner"><div class="popup-gallery__slider">';
					
					$.each($(".i-item_icon"), function() {
						$result += '<div class="popup-gallery__slider-item">' + createIconPopup($(this)) + '</div>';
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
						var $imgIcon       = $("> img", $itemIcon),
							$imgIconTitles = $("> img", $itemIconTitle),
							$imgArtist     = $("> img", $itemArtist),
							$aIcon         = $("> a", $itemIcon),
							$aIconTitles   = $("> a", $itemIconTitle),
							$aArtist       = $("> a", $itemArtist),
							sliderIcon, sliderIconTitle, sliderArtist;

						$aIcon.add($aArtist).add($aIconTitles).off(".desktopPopupOpen").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$(this).siblings("img").click();
						});
						
						$imgIcon.off(".desktopPopupOpen").on("click.mobilePopupOpen", function(e) {
							e.preventDefault();

							$("#overlay").fadeIn();
							$("#popup-icons").fadeIn();
							
							var index = $imgIcon.index(this);
							
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
						
						createMobileGallery();
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
            zoom        : 16,
            center        : new google.maps.LatLng(0, 0),
            mapTypeId    : google.maps.MapTypeId.ROADMAP
        };

        // create map
        var map = new google.maps.Map( $el[0], args);

        // add a markers reference
        map.markers = [];

        // add markers
        $markers.each(function(){

            add_marker( $(this), map );

        });

        // center map
        center_map( map );

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