"use strict";
var header = jQuery('.main_header'),
    header_w = header.width() + parseInt(header.css('padding-left')) + parseInt(header.css('padding-right')),
    header_h = header.height(),
    headerWrapper = jQuery('.header_wrapper'),
    headerScroll = jQuery('.header_scroll'),
    html = jQuery('html'),
    body = jQuery('body'),
    footer = jQuery('.footer_wrapper'),
    window_h = jQuery(window).height(),
    window_w = jQuery(window).width(),
    main_wrapper = jQuery('.main_wrapper'),
    site_wrapper = jQuery('.site_wrapper'),
    preloader_block = jQuery('.preloader'),
    fullscreen_block = jQuery('.fullscreen_block'),
    is_masonry = jQuery('.is_masonry'),
    grid_portfolio_item = jQuery('.grid-portfolio-item'),
    pp_block = jQuery('.pp_block'),
    head_border = 1;

jQuery(document).ready(function ($) {
    if (jQuery('.preloader').size() > 0) {
        setTimeout("jQuery('.preloader').fadeOut(500)", 1);
        setTimeout("jQuery('.peloader_logo').fadeOut(500)", 1);
    }
    if (body.hasClass('admin-bar') && window_w > 760) {
		jQuery('.logo').css('padding-top', parseInt(header.css('padding-top')) + jQuery('#wpadminbar').height());
    }
    content_update();
    var settings = {
        showArrows: false,
        autoReinitialise: true
    };
    if (window_w > 760) {
        headerScroll.jScrollPane(settings);
        headerScroll.scroll(function () {
            if (header.hasClass('hasScroll') && parseInt(headerScroll.find('.jspPane').css('top')) < 0) {
                header.addClass('top_shade');
            } else {
                header.removeClass('top_shade');
            }
            if (header.hasClass('hasScroll') && parseInt(headerScroll.find('.jspPane').css('top')) == header.find('.jspContainer').height() - header.find('.jspPane').height()) {
                header.addClass('no_bot_shade');
            } else {
                header.removeClass('no_bot_shade');
            }
        });
    }

    //Flickr Widget
    if (jQuery('.flickr_widget_wrapper').size() > 0) {
        jQuery('.flickr_badge_image a').each(function () {
            jQuery(this).append('<div class="flickr_fadder"></div>');
        });
    }

    //Main and Mobile Menu
    jQuery('.menu-item-has-children').children('a').click(function () {
        jQuery(this).next('ul').slideToggle(250);
        setTimeout("content_update()", 250);
        setTimeout("content_update()", 350);
    });
	
	//jQuery('.current-menu-parent').children('ul').slideDown(1);
	
    header.find('.header_wrapper').append('<a href="javascript:void(0)" class="menu_toggler"></a>');
    if (jQuery('.header_filter').size() > 0) {
        jQuery('.header_filter').before('<div class="mobile_menu_wrapper"><ul class="mobile_menu container"/></div>');
    } else {
        header.append('<div class="mobile_menu_wrapper"><ul class="mobile_menu container"/></div>');
    }
    jQuery('.mobile_menu').html(header.find('.menu').html());
    jQuery('.mobile_menu_wrapper').hide();
    jQuery('.menu_toggler').click(function () {
        jQuery('.mobile_menu_wrapper').slideToggle(300);
        jQuery('.main_header').toggleClass('opened');
    });
	if (jQuery('.site_wrapper').size() > 0) {
		setTimeout("jQuery('.site_wrapper').animate({'opacity' : '1'}, 500)", 500);
	} 
	if (jQuery('.fullscreen_block').size() > 0) {
		setTimeout("jQuery('.fullscreen_block').animate({'opacity' : '1'}, 500)", 500);
	}

    if (pp_block.size() > 0) {
        pp_center();
    }
});

jQuery(window).resize(function () {
    window_h = jQuery(window).height();
    window_w = jQuery(window).width();
    header_w = header.width() + parseInt(header.css('padding-left')) + parseInt(header.css('padding-right'));
    content_update();
});

jQuery(window).load(function () {
    content_update();
});

function content_update() {
    if (window_w > 760) {
        //site_wrapper.width(window_w - parseInt(body.css('padding-left')) - parseInt(body.css('padding-right'))).height(window_h - parseInt(body.css('padding-top')) - parseInt(body.css('padding-bottom')));

        if (body.hasClass('admin-bar')) {
            headerWrapper.css('min-height', window_h - parseInt(footer.css('padding-bottom')) - footer.height());
            headerScroll.height(window_h - parseInt(header.css('padding-top')) - parseInt(header.css('padding-bottom')));
        } else {
            headerWrapper.css('min-height', window_h - parseInt(footer.css('padding-bottom')) - footer.height());
            headerScroll.height(window_h - parseInt(header.css('padding-top')) - parseInt(header.css('padding-bottom')));
        }

        if (header.height() < header.find('.jspPane').height()) {
            header.addClass('hasScroll');
        } else {
            header.removeClass('hasScroll');
        }
    }
}

function gt3_get_blog_posts(post_type, posts_count, posts_already_showed, template_name, content_insert_class, categories, set_pad) {
    jQuery.post(gt3_ajaxurl, {
        action: "gt3_get_blog_posts",
        post_type: post_type,
        posts_count: posts_count,
        posts_already_showed: posts_already_showed,
        template_name: template_name,
        content_insert_class: content_insert_class,
        categories: categories,
        set_pad: set_pad
    })
        .done(function (data) {
            jQuery(content_insert_class).append(data);
            if (jQuery('.this_is_blog').size() > 0) {
				if (jQuery('.newAddedPosts').size() > 0) {
					jQuery('.newAddedPosts').find('.gallery_likes_add').bind('click',function(){
						var gallery_likes_this = jQuery(this);
						if (!jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'))) {
							jQuery.post(gt3_ajaxurl, {
								action:'add_like_attachment',
								attach_id:jQuery(this).attr('data-attachid')
							}, function (response) {
								jQuery.cookie(gallery_likes_this.attr('data-modify')+gallery_likes_this.attr('data-attachid'), 'true', { expires: 7, path: '/' });
								gallery_likes_this.addClass('already_liked');
								gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
								gallery_likes_this.find('span').text(response);
							});
						}
					});
					jQuery('.newAddedPosts').removeClass('newAddedPosts');
				}
					
                jQuery('.pf_output_container').each(function () {
                    if (jQuery(this).html() == '') {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('no_pf');
                    } else {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('has_pf');
                    }
                });
            }
            if (jQuery('.fw-portPreview-content').size() > 0) {
                port_setup();
            }
            if (is_masonry.size() > 0) {
                is_masonry.masonry('reloadItems');
                is_masonry.masonry();
            }
            if (jQuery('.fs_grid_portfolio').size() > 0) {
                setupGrid();
                grid_portfolio_item.unbind();
                grid_portfolio_item.bind({
                    mouseover: function () {
                        jQuery(this).removeClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height() + jQuery(this).find('.fs-port-cont').height());
                    },
                    mouseout: function () {
                        jQuery(this).addClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height());
                    }
                });
            }
            jQuery('.newLoaded').each(function () {
                jQuery(this).find('.gallery_likes_add').click(function () {
                    var gallery_likes_this = jQuery(this);
                    if (!jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'))) {
                        jQuery.post(gt3_ajaxurl, {
                            action: 'add_like_attachment',
                            attach_id: jQuery(this).attr('data-attachid')
                        }, function (response) {
                            jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'), 'true', {
                                expires: 7,
                                path: '/'
                            });
                            gallery_likes_this.addClass('already_liked');
                            gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
                            gallery_likes_this.find('span').text(response);
                        });
                    }
                });
                jQuery(this).removeClass('newLoaded');
            });
            setTimeout("animateList()", 300);
            jQuery(window).on('scroll', scrolling);
        });
}

function gt3_get_isotope_posts(post_type, posts_count, posts_already_showed, template_name, content_insert_class, set_pad, post_type_field) {
    jQuery.post(gt3_ajaxurl, {
        action: "get_portfolio_works",
        post_type: post_type,
        posts_count: posts_count,
        posts_already_showed: posts_already_showed,
        template_name: template_name,
        content_insert_class: content_insert_class,
        categories: categories,
        set_pad: set_pad,
		post_type_field: post_type_field		
    })
        .done(function (data) {
            if (data.length < 1) {
                jQuery(".load_more_works").hide("fast");
            }
            if (jQuery('.fw-portPreview-content').size() > 0) {
                port_setup();
            }
            var $newItems = jQuery(data);
            jQuery(content_insert_class).isotope('insert', $newItems, function () {
                jQuery(content_insert_class).ready(function () {
                    jQuery(content_insert_class).isotope('reLayout');
                });
                if (jQuery('.fs-port-cont').size() > 0) {
                    setTimeout("setupGrid()", 500);
                    setTimeout("setupGrid()", 1000);
                    setTimeout('jQuery(".fs_grid_portfolio").isotope("reLayout");', 1500);
                }
				jQuery('.newLoaded').each(function () {
					jQuery(this).find('.gallery_likes_add').click(function () {
						var gallery_likes_this = jQuery(this);
						if (!jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'))) {
							jQuery.post(gt3_ajaxurl, {
								action: 'add_like_attachment',
								attach_id: jQuery(this).attr('data-attachid')
							}, function (response) {
								jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'), 'true', {
									expires: 7,
									path: '/'
								});
								gallery_likes_this.addClass('already_liked');
								gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
								gallery_likes_this.find('span').text(response);
							});
						}
					});
					jQuery(this).removeClass('newLoaded');
				});				
            });
        });
}
function gt3_get_portfolio(post_type, posts_count, posts_already_showed, template_name, content_insert_class, categories, set_pad, post_type_field) {
    jQuery.post(gt3_ajaxurl, {
        action: "get_portfolio_works",
        post_type: post_type,
        posts_count: posts_count,
        posts_already_showed: posts_already_showed,
        template_name: template_name,
        content_insert_class: content_insert_class,
        categories: categories,
        set_pad: set_pad,
		post_type_field: post_type_field
    })
        .done(function (data) {
            jQuery(content_insert_class).append(data);
            if (jQuery('.this_is_blog').size() > 0) {
                jQuery('.pf_output_container').each(function () {
                    if (jQuery(this).html() == '') {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('no_pf');
                    } else {
                        jQuery(this).parents('.fw_preview_wrapper').addClass('has_pf');
                    }
                });
            }
            if (jQuery('.fw-portPreview-content').size() > 0) {
                port_setup();
            }
            if (is_masonry.size() > 0) {
                is_masonry.masonry('reloadItems');
                is_masonry.masonry();
            }
            if (jQuery('.fs_grid_portfolio').size() > 0) {
                setupGrid();
                grid_portfolio_item.unbind();
                grid_portfolio_item.bind({
                    mouseover: function () {
                        jQuery(this).removeClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height() + jQuery(this).find('.fs-port-cont').height());
                    },
                    mouseout: function () {
                        jQuery(this).addClass('unhovered');
                        jQuery(this).find('.grid-item-trigger').css('height', jQuery(this).find('img').height());
                    }
                });
            }
            jQuery('.newLoaded').each(function () {
                jQuery(this).find('.gallery_likes_add').click(function () {
                    var gallery_likes_this = jQuery(this);
                    if (!jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'))) {
                        jQuery.post(gt3_ajaxurl, {
                            action: 'add_like_attachment',
                            attach_id: jQuery(this).attr('data-attachid')
                        }, function (response) {
                            jQuery.cookie(gallery_likes_this.attr('data-modify') + gallery_likes_this.attr('data-attachid'), 'true', {
                                expires: 7,
                                path: '/'
                            });
                            gallery_likes_this.addClass('already_liked');
                            gallery_likes_this.find('i').removeClass('icon-heart-o').addClass('icon-heart');
                            gallery_likes_this.find('span').text(response);
                        });
                    }
                });
                jQuery(this).removeClass('newLoaded');
            });
            setTimeout("animateList()", 300);
            jQuery(window).on('scroll', scrolling);
        });
}

function animateList() {
    jQuery('.loading:first').removeClass('loading').animate({'z-index': '15'}, 200, function () {
        animateList();
        if (is_masonry.size() > 0) {
            is_masonry.masonry();
        }
    });
};

function workCheck() {
    if (jQuery('.fs_blog_module').height() < parseInt(fullscreen_block.css('min-height'))) {
        get_works();
    } else {
        fullscreen_block.addClass('cheked');
    }
}

function scrolling() {
    var chk_height = jQuery('body').height() - jQuery(this).height() - header.height() - footer.height() - 20;
    if (jQuery(this).scrollTop() >= chk_height) {
        jQuery(this).unbind("scroll");
        get_works();
    }
}

var setTop = 0;
function pp_center() {
    var pp_block = jQuery('.pp_block');
    setTop = (window_h - pp_block.height()) / 2;
    pp_block.css('top', setTop + 'px');
    pp_block.removeClass('fixed');
}
