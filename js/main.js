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
	popupIcon: '<div class="i-item__popup"><div class="i-item__popup-frame"></div><a href="{url}" class="i-item__popup-img"><img src="{img}" alt="" class="i-item__popup-work"></a><div class="i-item__popup-title">{title}<span>{author}</span></div><div class="i-item__popup-tags">{tags}</div></div>',
	
	popupArtist: '<div class="i-item__popup i-item__popup_person"><div class="i-item__popup_map"><img src="{map}" alt=""></div><img src="{artist}" alt="" class="i-item__popup-artist"><a href="{artistUrl}" class="i-item__popup-name">{name}</a><div class="i-item__popup-place">{place}</div><div class="i-item__popup-works">{works}</div></div>'
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
				
				if('matchMedia' in window && window.matchMedia('(min-width: 768px)').matches) {
					$("body")[st > $(window).height() ? "addClass" : "removeClass"]("header-fixed");
				}
			});
		}
		
		if($("[data-toggle]").length) {
			$("[data-toggle]").on("click", function() {
				var $elem = $($(this).data("toggle"));
				
				$elem.length && $elem.slideToggle(0);
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
					
					$container.isotope({
						sortBy: 'yearDown'
					});
				});
				
				$sortByYearsUp.on("click", function() { // sort by years
					changeActive($(this));
					
					$container.isotope({
						sortBy: 'yearUp'
					});
				});
				
				$sortByLettersDown.on("click", function() { // sort by letters
					changeActive($(this));
					
					$container.isotope({
						sortBy: 'letterDown'
					});
				});
				
				$sortByLettersUp.on("click", function() { // sort by letters
					changeActive($(this));
					
					$container.isotope({
						sortBy: 'letterUp'
					});
				});
				
				$sortByTitleDown.on("click", function() { // sort by title
					changeActive($(this));
					
					$container.isotope({
						sortBy: 'titleDown'
					});
				});
				
				$sortByTitleUp.on("click", function() { // sort by title
					changeActive($(this));
					
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
						return;
					}
					
					$this.addClass("active").siblings(".active").removeClass("active");
				}
				
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
						.replace('{map}', data.map)
						.replace('{artist}', data.artist)
						.replace('{place}', data.place)
						.replace('{name}', name[0])
						.replace('{artistUrl}', name[1]);
					
					works = data.works.split(",");
					
					for(var i = 0, j = works.length; i < j; i += 1) {
						var str = works[i].split("?");
						
						worksResult += ('<a href="' + str[1] + '"><img src="' + str[0] + '" alt=""></a>');
					}
					
					html = html.replace('{works}', worksResult);
					
					return html;
				}
				
				$("> img", $itemIcon).on("dblclick.desktopPopupOpen", function(e) {
					var $opened = $(".opened-popup"),
						$closest = $(this).closest(".i-item");
					
					if($opened.length && !$closest.hasClass("opened-popup")) {
						$opened.removeClass("opened-popup").find(".i-item__popup").hide();
					}
					
					if($closest.hasClass("opened-popup")) {
						$closest.removeClass("opened-popup").find(".i-item__popup").hide();
						return;
					}
					
					var $popup = $(".i-item__popup", $closest);
					
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
				});
				
				$("body").on("dblclick", ".i-item__popup-frame", function() {
					var $closest = $(this).closest(".i-item");
					if($closest.hasClass("opened-popup")) {
						$closest.removeClass("opened-popup").find(".i-item__popup").hide();
						return;
					}
				});
				
				$("> img", $itemArtist).on("dblclick.desktopPopupOpen", function(e) {
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
					}
				});
				
				$("body").on("click", function(e) {
					if(!($(e.target).closest(".isotope").length || $(e.target).hasClass("isotope"))) {
						$(".opened-popup").length && $(".opened-popup").removeClass("opened-popup").find(".i-item__popup").hide();
					}
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
				
				(function() {
					if(isSmallScreen) {
						var $imgIcon = $("> img", $itemIcon),
							$imgArtist = $("> img", $itemArtist),
							slider;
						
						$imgIcon.off("dblclick.desktopPopupOpen").on("click.mobilePopupOpen", function() {
							$("#overlay").fadeIn();
							$("#popup-icons").fadeIn();
							
							var index = $imgIcon.index(this);
							
							if(!$("#popup-icons").hasClass("slider-ready")) {
								$("#popup-icons").addClass("slider-ready");
								
								slider = $("#popup-icons .popup-gallery__slider").bxSlider({
									pager: false,
									controls: false,
									slideWidth: 230,
									startSlide: index
								});
							} else {
								slider.goToSlide(index);
							}
						});
						
						//$imgArtist.off("dblclick.desktopPopupOpen");
						
						createMobileGallery();
						
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
}(jQuery));