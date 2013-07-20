/*
 * JQuery "MT Flycards for Woocommerce 2.0.10 and later" 
 * version: 1.0.0 
 * licensed under GPL licenses, using the same terms as jQuery.
 * copyright(c) 2012/13 Marco Tomaselli sys@dynup.org 
 */
;
(function($, window, document, undefined) {
    "use strict";
    /**
     * new instance of flycards
     */
    function Flycard(element, options) {

	this.$el = $(element);
	this.opts = $.extend({}, $.fn.flycards.defaults, options);
	this._init();

    }

    /**
     * flycard methods
     */
    Flycard.prototype = {
	    /**
	     * init
	     * 
	     * @private
	     */
	    _init : function() {

		

		if ($('body').hasClass('single single-product')) {

		    this._is_single();
		    /*
		     * Binding elements
		     */
		    this._bind_add_to_cart();

		} else {
		    // create head
		    this._head();
		    // create contents
		    this._contents();
		    /*
		     * Binding elements
		     */
		    this._bind_accordion();
		    if (this.opts.isActive.ajax) {
			this._bind_catalog();
			this._bind_ordering();
		    }
		    this._bind_filters();
		    this._bind_add_to_cart();

		}

	    },
	    /**
	     * Pagination 
	     * applying jQuery-UI to Pagination
	     * @private
	     */
	    _pagination:function(){
		var opts=this.opts;
		var $el=this.$el;
		//remove woocommerce class from pagination  for apply jQuery-UI to pagination
		$el.find(opts.wcNavPagination).removeClass('woocommerce-pagination');
		$el.find(opts.wcPageNumberAnchor).button();
		var current_page=$el.find(opts.wcPageNumberCurrent).wrap('<a class="page-numbers current"></a>').button();
		current_page.button("disable");
		if(!mtflycards.infinitescroll)
		$el.find(opts.paginationContent).css("visibility","visible");
		
	    },
	    
	   
	    /**
	     * Create Tabs and 
	     * binding its elements
	     * @private
	     */
	    _head : function() {
		
		var instance = this;
		var opts = instance.opts;
		var $nav= instance.$el.find(opts.nav);
		var $tabs = $nav.find(opts.tabs);
		var $bar=$nav.find(opts.bar);
		
		//binding tabs filter
		$(window).bind(
			'tabfiltersrefresh',
			function(e, finished) {
			    var $tabsCatFilters = $tabs.find(opts.tabsCatfilters);
			    var $tabsTagFilters = $tabs.find(opts.tabsTagfilters);
			    if ($tabsCatFilters.length)
			    (!finished && mtflycards.infinitescroll)
			    || !instance.opts.isActive.catFilters
			    || !$tabsCatFilters.children().length ? $tabs
				    .tabs("disable", parseInt($tabsCatFilters.attr(
				    "aria-labelledby")
				    .replace("ui-id-", "")) - 1) : $tabs /*3 is tabs before page categories tab*/
				    .tabs("enable", parseInt($tabsCatFilters.attr(
				    "aria-labelledby")
				    .replace("ui-id-", "")) - 1);
				    
				    if($tabsTagFilters.length)
				    (!finished && mtflycards.infinitescroll)
				    || !instance.opts.isActive.tagFilters
				    || !$tabsTagFilters.children().length ? $tabs
					    .tabs("disable", parseInt($tabsTagFilters.attr(
					    "aria-labelledby")
					    .replace("ui-id-", "")) - 1) : $tabs
					    .tabs("enable", parseInt($tabsTagFilters.attr(
					    "aria-labelledby")
					    .replace("ui-id-", "")) - 1);
			});
		
		
		//create tabs
		$tabs.tabs(opts.tabs_opts);
		$bar.tabs();
		
		//fix the classes for tabs to bottom
		$nav.find( opts.tabs+' .ui-tabs-nav,'+opts.tabs+' .ui-tabs-nav > *' )
		.removeClass( "ui-corner-all ui-corner-top" )
		.addClass( "ui-corner-bottom" )
		;
		// move the nav to the bottom
		$nav.find( opts.tabs+' .ui-tabs-nav' ).appendTo( opts.tabs);
		 
		
		//Navigation Bar and Tabs ready to be visible
		$nav.css('visibility','visible');
	    },
	    
	    /**
	     * accordion categories menu
	     * 
	     * @private
	     */
	    _bind_accordion : function() {
		
		this.$el.find(this.opts.menuChildIcon).live('click.flycards',
			function() {
		    // break event
		    return false;
		});

		this.$el.find(this.opts.menuHeadIcon).live(
			'click.flycards',
			function() {

			    var $indent = 0;
			    var $this = $(this);
			    var $thisChildren = $this.parent().next();
			    // indent children
			    $this.parents().each(function() {
				if ($(this).hasClass('fc-accordion'))
				    return false;
				$indent += 2;
			    });
			    $thisChildren.css('padding-left', $indent + 'px')
			    .toggle();
			    // toggle icon
			    $this.hasClass('ui-icon-triangle-1-e') ? $this
				    .removeClass('ui-icon-triangle-1-e').addClass(
				    'ui-icon-triangle-1-s') : $this
				    .removeClass('ui-icon-triangle-1-s').addClass(
				    'ui-icon-triangle-1-e');

				    // break event
				    return false;
			})
			//.parent().next().hide()
			;

	    },
	   
	    /**
	     * bind category tabs
	     * 
	     * @private
	     */
	    _bind_catalog : function() {

		var instance = this;
		var opts = this.opts;
		this.$el.find(this.opts.tabsCat + ' a, '+this.opts.tabsCatSubcat+' a,'+opts.wcPageNumberAnchor).live(
			'click.flycards',
			function() {
			    var page_link = $(this).attr('href');
			    var frm = instance.$el
			    .find(opts.wcOrdering);
			    frm.attr('action', page_link);
			    frm.find('input[name=s]').remove();
			    frm.submit();
			    // break link action
			    return false;
			});

	    },
	    /**
	     * bind ordering form
	     * 
	     * @private
	     */
	    _bind_ordering : function() {
		
		var instance = this;
		var $frm = instance.$el.find(this.opts.wcOrdering);
		
		
		$frm.on('submit',function(e) {
		   e.preventDefault();
		    var $this= $(this);
		    $.ajax({
			beforeSend : function() {

			    instance.$el.find(instance.opts.nav).block({
				theme : true,
				title : mtflycards.js_loading,
				message : mtflycards.js_wait

			    });
			    instance.$el.find(instance.opts.cardsContent).block({
				message : null,
				overlayCSS : {
				    backgroundColor : 'none',
				    // opacity: 0.6,
				    cursor : 'wait'
				}
			    }

			    );
			    instance.$el.find(instance.opts.pagination).block({
				message : null,
				overlayCSS : {
				    backgroundColor : 'none',
				    // opacity: 0.6,
				    cursor : 'wait'
				}
			    });
			},
			type : $this.attr('method'),
			url : $this.attr('action'),
			data : $this.serialize()
			 
		    })
		    .done($.proxy(instance, '_contents'))
		    .error( function(XMLHttpRequest, textStatus, errorThrown) {
		        console.log(textStatus);
		        location.href=window.location;
		    })
		    .always(function() {
			instance.$el.find(instance.opts.nav).unblock();
			instance.$el.find(instance.opts.cardsContent).unblock();
			instance.$el.find(instance.opts.pagination).unblock();
		    });
		 
		});

	    },
	    /**
	     * bind categories and tags filters
	     * 
	     * @private
	     */
	    _bind_filters : function() {

		var opts = this.opts;
		var $el = this.$el;
		var $liveFilters = $el.find(opts.tabsCatfilters + ' a, '
			+ opts.tabsTagfilters + ' a');

		// bind filters link
		$liveFilters.live('click.flycards', function(e) {
		    e.preventDefault();
		    var $this = $(this);
		    var selector = '';
		    var filters;
		    var $cards = $el.find(opts.cardsContentCard).hide();
		    var $tabs = $el.find(opts.tabs);
		    
		    $this.hasClass(opts.chosenClass) ? $this
			    .removeClass(opts.chosenClass+' ui-state-focus').css('border','0') : $this
			    .addClass(opts.chosenClass+' ui-state-focus').css('border','0');
		    
			    $this.attr('data-filter').match('^\.' + opts.catPrefix)
		    ?$el.find(opts.tabsTagfilters+' a').each(function(){$(this).removeClass(opts.chosenClass);})
		    :$el.find(opts.tabsCatfilters+' a').each(function(){$(this).removeClass(opts.chosenClass);});
		    
		    
			    filters = $.map($tabs.find('a.' + opts.chosenClass).get(),
				    function(val, i) {
				return $(val).attr('data-filter').split(/\s+/);
			    });
			    filters.length > 0 ? selector = filters.join(', ')
				    : selector = opts.cardsContentCard;
			    $.map($cards.parent().find(selector).find('h3').get().sort(
				    function(a, b) {
					return $(a).text() > $(b).text() ? 1 : -1;
				    }).reverse(), function(val, i) {
				$(val).parent().show().insertBefore(
					$(val).parent().siblings().first(":visible"));
			    });
			    $cards.parent().masonry('reload');
		});},
		/**
		 * DESC sorting text collection of elements
		 * @param $el
		 * @private
		 */
		_sort_elements_text:function($el){
		    $.map($el.contents().get().sort(
			    function(a, b) {
				return $(a).text() > $(b).text() ? 1 : -1;
			    }).reverse(), function(val, i) {
			
			$(val).insertBefore(
				$(val).siblings().first());
		    });
		},
	    /**
	     * add to cart
	     * 
	     * @private
	     */
	    _bind_add_to_cart : function() {

		this.$el.find(this.opts.cardsContentCard + ' .add_to_cart_button')
		.live('click.flycards', function() {
		    var $thisButton = $(this);
		    $thisButton.parent().block({
			message : null
		    });
		    $('body').on('added_to_cart', function() {
			$thisButton.parent().unblock();
			$thisButton.button({
			    icons : {
				primary : 'ui-icon-plus'
			    }
			});
			$thisButton.parent().find('a.added_to_cart')
			.button({
			    text:false,
			    icons:{primary:'ui-icon-arrowreturnthick-1-e',secondary:'ui-icon-cart'}
			});
		    });
		});

	    },

	    /**
	     * single product page procedure when single product page is detected
	     * 
	     * @private
	     */
	    _is_single : function() {

		var instance = this;
		var opts = instance.opts;
		var $el = instance.$el;

		var $upsells = $el.find(opts.wcUpsells);
		var $related = $el.find(opts.wcRelated);
		var $cross = $el.find(opts.wcCrossSells);
		
		$upsells.find('h2').prependTo($upsells.parent().parent());
		$related.find('h2').prependTo($related.parent().parent());
		
		$related.attr('id', 'related').find(opts.cardsContentCard).each(function() {
		    instance._card_restyle(this, 'related');
		}).end().parent().masonry(opts.masonry_opts).ImageOverlay(
			opts.imageoverlay_opts);
		
		$upsells.attr('id', 'upsells').find(opts.cardsContentCard).each(function() {
		    instance._card_restyle(this, 'upsells');
		}).end().parent().masonry(opts.masonry_opts).ImageOverlay(
			opts.imageoverlay_opts);
		
		$cross.attr('id', 'cross').wrap('<div class="fc-single-box-title"/>');
		$cross.find('h2').prependTo($cross.parent().parent());
		$cross.find(opts.cardsContentCard).each(function() {
		    instance._card_restyle(this, 'cross', function() {
			$(this).find('br').remove();
			$(this).find('p:not(div > p)').contents().unwrap();
			$(this).find("p, a").filter(function() {
			    $this = $(this);
			    return (!$.trim($this.text()).length && !$this.has('img').length);
			}).remove();
			
		    });
			});
		$cross.parent().masonry(opts.masonry_opts).ImageOverlay(
			opts.imageoverlay_opts);
		
		
		
		
		},

	    /**
	     * content build and update
	     * 
	     * @param data
	     * @private
	     */
		_contents : function(data) {

		    var instance = this;
		    var opts = instance.opts;
		    var $el = instance.$el;
		    var sc = function(option) {
			return option.substring(option.lastIndexOf('.') + 1);
		    };
		    var valid_filter = function(datafilter) {
			return $el.find(opts.cardsContentCard).length != $el.find(
				opts.cardsContent).find(datafilter).length ? true
					: false;
		    };
		    /*
		     * update page fragments
		     */
		    if (data) {
			var $data = $(data);
			$.each([ opts.cardsContent, opts.phtags, opts.phcats,
			         opts.pageTitle, opts.pagination, opts.breadcrumbs,
			         opts.tabsTagfilters, opts.tabsCatfilters,
			         opts.tabsCatSubcat ], function(index, opt) {
			    $el.find(opt).html($data.find(opt).contents());
			});
		    }
		    // restyle cards
		    this.$el.find(opts.cardsContentCard).each(function() {
			instance._card_restyle(this);
		    }).end()
		    // append categories card to sub categories tab
		    .find(opts.tabsCatSubcatCat).removeClass(sc(opts.cardsContentCard))
		    .appendTo(this.$el.find(opts.tabsCatSubcat)).end().end()
		    // append valid filters to tabs
		    .find(opts.tabsCatfilters).append(
			    $el.find(opts.phcats).find('a').filter(function() {
				if (valid_filter($(this).attr('data-filter')))
				    return instance._capitalizeA(this);
				else
				    $(this).remove();
			    })).end().find(opts.tabsTagfilters).append(
				    $el.find(opts.phtags).find('a').filter(function() {
					if (valid_filter($(this).attr('data-filter')))
					    return instance._capitalizeA(this);
					else
					    $(this).remove();
				    }));
		    this._sort_elements_text($el.find(opts.tabsCatfilters));
		    // create plugins data
		    this._build();
		    // refresh tab filters
		    $(window).trigger('tabfiltersrefresh');
		    // show cards
		    $el.find(opts.cards).css('visibility', 'visible');
		    // pagination
		    this._pagination();

		},
		/**
	     * instance build and clean plugin data generated by external used
	     * and required by flycards
	     * 
	     * @param required
	     *                {Array}
	     * @param destroy
	     *                {Boolean}
	     * @private
	     */
		_build : function(required, destroy) {

		    var instance = this;
		    if (!required)
			required = [
			            [ 'masonry', instance.opts.cardsContent ],
			            [ 'ImageOverlay', instance.opts.cardsContent ],
			            [ 'infinitescroll', instance.opts.cardsContent,
			              '_scroll' ] ];

		   var fname, elname, fzName, fz, fopts, $elfound, data, clean, build;
		    for ( var i = 0; i < required.length; i++) {
			
			fname = required[i][0];
			
			switch (fname) {
			
			case "ImageOverlay":
			    if (!instance.opts.isActive.imageOverlay)
				continue;
			    break;

			case "infinitescroll":
			    if (!instance.opts.isActive.infscr)
				continue;
			    break;
			}

			elname = required[i][1];
			fzName = required[i][2];
			fz = instance[fzName];
			fopts = instance.opts[(required[i][0]).toLowerCase() + '_opts'];
			$elfound = instance.$el.find(elname);
			data = $elfound.data(fname);
			build = function() {
			    $.isFunction(fz) ? $elfound[fname](fopts, $.proxy(instance,
				    fzName)) : $elfound[fname](fopts);
			};
			clean = function() {
			    $elfound.unbind('.' + fname).die('.' + fname).undelegate();
			    if ($.isFunction($elfound[fname]))
				$elfound[fname]('destroy');
			    $elfound.data(fname, null).removeData(fname);
			};
			if (!destroy && !data) {
			    build();
			} else {
			    clean();
			    if (!destroy)
				build();
			}

		    }

		},
	    /**
	     * @private
	     * @param el
	     * @param targetContainer
	     * @param callback
	     */
	    _card_restyle : function(el, targetContainer, callback) {

		if (targetContainer == null)
		    targetContainer = this.opts.cardsContent.replace('#', '');
		var $this = $(el);
		var parents_count = 0;
		var found = false;
		$this.parents().each(function() {
		    if ($(this).attr('id') == targetContainer) {
			found = true;
			return false;
		    }
		    parents_count += 1;
		});

		if (found && parents_count >= 1)
		    for ( var int = 0; int < parents_count; int++) {
			$this
			.addClass($this.parent().attr('class')).removeClass('first last products product')
			.unwrap();
		    }

		if(!$this.is(this.opts.tabsCatSubcatCat))	
		$this
		.css({width:mtflycards.card_width, height:mtflycards.card_height})
		//background color of img
		.find('img').parent().css('background',mtflycards.bgc_product_img).addClass('fc-img-anchor').end().end()
		//card title
		.find('h3').each(
			function() {
			    $this.append($(this).addClass('fc-card-title'));
			}).end()
			// span price
			.find('span.price').each(function() {
			    $this.append($(this).addClass('ui-state-highlight'));
			}).end()
			// buttons
			.find('a.button').each(function() {
			    $this.append($(this).removeClass('button'));
			    if ($(this).hasClass('product_type_simple'))
				$(this).button({
				    icons : {
					primary : 'ui-icon-cart'
				    }
				});
			    else if ($(this).hasClass('product_type_variable'))
				$(this).button({
				    icons : {
					primary : 'ui-icon-extlink'
				    }
				});
			    else
				$(this).button({
				    icons : {
					primary : 'ui-icon-info'
				    }
				});
			}).addClass('fc-card-button').end()
			// rating
			.find('div.star-rating').each(function() {
			    $this.append($(this).css('color',mtflycards.star_color));
			}).end()
			// onsale
			.find('span.onsale').each(function(){
			    $(this).addClass('ui-state-focus ui-priority-primary');
			}).removeClass('onsale').addClass('fc-onsale').end()
			.addClass('ui-widget-content')
			;

		if ($.isFunction(callback))
		    callback.call($this);
		else if (callback != null)
		    console.error('last param must be a function');
		else
		    return $this;

	    },
	    /**
	     * scroll
	     * 
	     * @param instance
	     * @param newElements
	     * @private
	     */
	    _scroll : function(newElements) {

		var instance = this;
		var valid_filter = function(datafilter) {
		    return instance.$el.find(instance.opts.cardsContentCard).length != instance.$el
		    .find(instance.opts.cardsContent).find(datafilter).length ? true
			    : false;
		};

		if (!newElements)
		    return;

		var $aCatsFilter = this.$el.find(instance.opts.tabsCatfilters)
		.find('a');
		var $aTagsFilter = this.$el.find(instance.opts.tabsTagfilters)
		.find('a');
		var catsFilter = $.map($aCatsFilter.get(), function(val, i) {
		    return $(val).attr('data-filter').replace(/\.+/g, '').split(
		    /\s+/);
		});
		var tagsFilter = $.map($aTagsFilter.get(), function(val, i) {
		    return $(val).attr('data-filter').replace(/\.+/g, '').split(
		    /\s+/);
		});

		var $newElems=$(newElements).css({
		    opacity : 0
		});
		$newElems.each(
			function() {
			    instance._card_restyle(this);
			    $.each($(this).attr('class').split(/\s+/), function(
				    index, item) {
				if(!valid_filter('.'+item)) return;
				if (item.match('^' + instance.opts.catPrefix)
					|| item
					.match('^'
						+ instance.opts.tagPrefix))

				    // console.log(item + " ["+tagsFilter.join(',')+catsFilter.join(',')+"]");
				    if (item.match('^' + instance.opts.catPrefix)
					    && $.inArray(item, catsFilter) == -1) {
					$aCatsFilter.parent().append(
						'<a href="#" data-filter=".'
						+ item
						+ '" >'
						+ instance
						._taxItemName(item)
						+ '</a>');
					catsFilter.push(item);

				    } else if (item.match('^'
					    + instance.opts.tagPrefix)
					    && $.inArray(item, tagsFilter) == -1) {
					$aTagsFilter.parent().append(
						'<a href="#" data-filter=".'
						+ item
						+ '" >'
						+ instance
						._taxItemName(item)
						+ '</a>');
					tagsFilter.push(item);

				    }
			    });
			}).imagesLoaded(function() {
			   $(this).animate({
				opacity : 1
			    });
			}).parent().masonry('appended', $newElems, true);
			
			this._build([[ 'ImageOverlay', instance.opts.cardsContent ]]);
			
		//sorting categories filter
		this._sort_elements_text(this.$el.find(this.opts.tabsCatfilters));	
		//refresh tab filters
		this.$el.find(this.opts.tabs).trigger('tabfiltersrefresh');

	    },

	    /**
	     * helper rebuild taxonomy name for filters
	     * 
	     * @param item
	     * @returns
	     * @private
	     */
	    _taxItemName : function(item) {

		var regex = new RegExp('^' + this.opts.tagPrefix + '|^'
			+ this.opts.catPrefix);
		return item.replace(regex, '').replace(/_amp_/g,' & ').replace(/_/g, ' ').toLowerCase()
		.replace(/^.|\s\S/g, function(fc) {
		    return fc.toUpperCase();
		});
	    },
	    /**
	     * capitalize text of link text content
	     * 
	     * @param $el
	     * 
	     * @private
	     */
	    _capitalizeA : function(el) {
		var $this = $(el);
		return $this.contents().each(
			function(i, a) {
			    if(a.textContent)
			    a.textContent = a.textContent.toLowerCase().replace(
				    /^.|\s\S/g, function(fc) {
					return fc.toUpperCase();
				    });
			});
	    },

	    /**
	     * flycards instance destroy
	     * 
	     * @returns {Boolean}
	     */
	    destroy : function() {
		var instance = this;

		var data =instance.$el.data('flycards');
		if (!data)
		    return;
		// destroy and unbind flycards data
		instance.$el.data('flycards', null).removeData('flycards').unbind(
		'.flycards').die('.flycards');
		// destroy used plugin
		this._build(null,true);
		console.log('flycards destroyed');
	    },
	    /**
	     * Dialog message this method is for testing
	     * not currently used
	     *@private
	     */
	    _dialog : function(title, message, container) {

		if (!this.$el.find(opts.cardsContentCard).length)
		    $(
			    "<div id='dialog' title='" + title + "'>" + message
			    + "</div>").dialog({
				modal : false,
				position : {
				    my : "center",
				    at : "center",
				    of : container
				},
				resizable : false,
				maxHeigth : 200,
				maxWidth :  300,
				buttons : [ {
				    text : "Ok",
				    click : function() {
					$(this).dialog("close");
				    }
				} ]
			    });

	    }

    };

    /**
     * flycards instance logic
     */
    $.fn.flycards = function(options) {
	return this
		.each(function() {
		    var instantiated = $.data(this, 'flycards');
		    if ((typeof options === 'object' && !instantiated)
			    || (!instantiated && !options)) {
			$.data(this, 'flycards', new Flycard(this, options));
		    } else if (instantiated && typeof options === 'string'
			    && options.charAt(0) !== "_") {
			var args = Array.prototype.slice.call(arguments, 1);
			$.isFunction(Flycard.prototype[options]) ? Flycard.prototype[options]
				.apply(instantiated, args)
				: console
					.log('flycards: no method corresponding to '
						+ options);
		    } else {
			if (instantiated) {
			    console.log('flycards: just instantiated');
			} else {
			    console
				    .log('flycards: no yet instantiated or not instantiated properly');
			}
		    }
		});
    };

    /**
     * flycards and external jQuery plugin default settings
     */
    $.fn.flycards.defaults = {

	    //wrapped
	    cards : '#fc-cards',
	    cardsContent : '#fc-cards-content',
	    cardsContentCard : 'div.fc-cards-content-card',
	    nav : '#fc-nav',
	    bar : '#fc-bar',
	    tabs : '#fc-tabs',
	    tabsCat : '#fc-tabs-cat',
	    tabsCatfilters : '#fc-tabs-catfilters',
	    tabsTagfilters : '#fc-tabs-tagfilters',
	    tabsCatAccordion : '#fc-tabs-cat-accordion',
	    tabsCatSubcat : '#fc-tabs-cat-subcat',
	    tabsCatSubcatCat : 'div.fc-tabs-cat-subcat-cat',
	    menuHead:'li.fc-menu-head',
	    menuHeadIcon : 'span.fc-menu-head-icon',
	    menuChildIcon : 'span.fc-menu-child-icon',
	    phcats : '#fc-phcats',
	    phtags : '#fc-phtags',
	    breadcrumbs : '#fc-breadcrumbs',
	    pagination : '#fc-pagination',
	    paginationContent : '#fc-pagination-content',
	    pageTitle:'h1.page-title',
	    //woocommerce
	    wcNavPagination:'nav.woocommerce-pagination',
	    wcPageNumberAnchor:'a.page-numbers',
	    wcPageNumberCurrent:'span.page-numbers.current',
	    wcOrdering : 'form.woocommerce-ordering',
	    wcUpsells : 'div.upsells',
	    wcCrossSells : 'div.cross-sells',
	    wcRelated : 'div.related',
	    //misc
	    catPrefix : 'cat_',
	    tagPrefix : 'tag_',
	    chosenClass : 'fc-chosen',
	    isActive:{
		catFilters:mtflycards.show_catsf_tab,
		tagFilters:mtflycards.show_tagsf_tab,
		imageOverlay:mtflycards.pe_image_overlay,
		ajax:mtflycards.ajax_page_load,
		infscr:mtflycards.infinitescroll
	    },
	   

	    infinitescroll_opts : {
		navSelector  : "nav.woocommerce-pagination",            
                nextSelector : "a.next",    
		itemSelector : "div.fc-cards-content-card" ,         
		contentSelector:"#fc-cards-content",
		loading:{
		    selector: "#fc-pagination",
		    msgText:mtflycards.js_loading_next_page,
		    finishedMsg:mtflycards.js_no_more_pages
		},
		errorCallback:function(){
		    
		    $(window).trigger('tabfiltersrefresh',1);
		    
		}
    			
	    },
	    masonry_opts : {
		itemSelector : 'div.fc-cards-content-card',
		isAnimated : true,
		animationOptions : {
		    duration : parseInt(mtflycards.animation_time),
		    easing : mtflycards.animation,
		    queue: false
		}
	    },
	    imageoverlay_opts : {
		always_show_overlay : false,
		animate : true,
		border_color : 'none',
		overlay_color : mtflycards.bgc_excerpt,
		overlay_origin : 'top',
		overlay_speed : 'fast',
		overlay_text_color : mtflycards.fg_excerpt
	    },
	    tabs_opts : {
		collapsible: true,
		heightStyle:'content',
		selected:-1
		    
	    }
    };
    
})(jQuery, window, document);