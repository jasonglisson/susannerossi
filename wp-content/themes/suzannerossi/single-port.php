<?php 
if ( !post_password_required() ) {
/* LOAD PAGE BUILDER ARRAY */
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
$pf = get_post_format();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];

/* ADD 1 view for this post */
$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);

$portfolioType = gt3_get_theme_option('default_portfolio_style');
if (isset($gt3_theme_pagebuilder['settings']['portfolio_style'])) {	
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'simple-portfolio-post') { 
		$portfolioType = 'simple-portfolio-post';
	}
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'fw-portfolio-post') { 
		$portfolioType = 'fw-portfolio-post';
	}
	if ($gt3_theme_pagebuilder['settings']['portfolio_style'] == 'ribbon-portfolio-post') { 
		$portfolioType = 'ribbon-portfolio-post';
	}
}
if ($portfolioType == 'fw-portfolio-post' || $portfolioType == 'ribbon-portfolio-post') {
	get_header('fullscreen');
} else {
	get_header();
}
$all_likes = gt3pb_get_option("likes");
the_post();
?>
    <script>
		jQuery(document).ready(function($){
            jQuery('.gallery_likes_add').click(function(){
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
		});
	</script>

<?php if ($portfolioType == 'simple-portfolio-post') { ?>
    <div class="content_wrapper">
        <div class="container simple-post-container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
                <div
                    class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                    <div class="row">						
                        <div class="posts-block simple-post <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea">
                                <div class="row">
                                    <div class="span12 module_cont module_blog module_none_padding module_blog_page">
                                        <div class="blog_post_page">
											<div class="single_post_top">
												<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                                                    <h1 class="blogpost_title"><?php the_title(); ?></h1>
                                                <?php } ?>                                            
                                                <div class="prev_next_links">
                                                    <div class="post_close"><a href="<?php echo esc_js("javascript:history.back()");?>"></a></div>
													<?php previous_post_link('<div class="fright_top">%link</div>', '') ?>
													<?php next_post_link('<div class="fleft_top">%link</div>', '') ?>
                                                </div>
                                            </div>
                                            <div class="blogpreview_top">
                                                <div class="listing_meta">
                                                    <span><i class="icon-folder"></i>
														<?php 
                                                            $terms = get_the_terms( get_the_ID(), 'portcat' );
                                                            if ( $terms && ! is_wp_error( $terms ) ) {
                                                                $draught_links = array();
                                                                foreach ( $terms as $term ) {
                                                                    $draught_links[] = '<a href="'.get_term_link($term->slug, "portcat").'">'.$term->name.'</a>';
                                                                }
                                                                $on_draught = join( ", ", $draught_links );
                                                                $show_cat = true;
                                                            }
                
                                                            if ($terms !== false) {
                                                                echo $on_draught;
                                                            } else {
                                                                echo "Uncategorized";
                                                            }
                                                        ?>                                                    
                                                    </span>
                                                    <?php 
														if (isset($gt3_theme_pagebuilder['page_settings']['icons']) ? $socicons = $gt3_theme_pagebuilder['page_settings']['icons'] : $socicons = false);
														if (is_array($socicons)) {
															foreach ($socicons as $key => $value) {
																//$compile .= $value['data-icon-code'] . $value['name'] . $value['link'];
																if ($value['link'] == '') {
																	echo '<span class="skill_ico"><i class="'.$value['data-icon-code'].'"></i>'.$value['name'].'</span>';
																} else {
																	echo '<span class="skill_ico"><a href="'.$value['link'].'" title="'.$value['name'].'"><i class="'.$value['data-icon-code'].'"></i>'.$value['name'].'</a></span>';
																}
															}
														}												
													?>
                                                    <?php the_tags("<span><i class='icon-tag'></i>", ', ', '</span>'); ?>
                                                    <span><i class="icon-comments"></i><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>                                                    
                                                </div>                                        

                                                <div class="likes_icons">
                                                <?php echo'
                                                    <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
                                                    <div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_port_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port_post">
                                                        <i class="stand_icon '.(isset($_COOKIE['like_port_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
                                                        <span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
                                                    </div>
													'; ?>
                                                    <a target="_blank"
                                                       href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"
                                                       class="top_socials share_facebook"><i
                                                            class="stand_icon icon-facebook-square"></i></a>
                                                    <a target="_blank"
                                                       href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink(); ?>"
                                                       class="top_socials share_tweet"><i class="stand_icon icon-twitter"></i></a>                                                       
                                                    <a target="_blank"
                                                       href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"
                                                       class="top_socials share_gplus"><i class="icon-google-plus-square"></i></a>
                                                    <a target="_blank"
                                                       href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo (strlen($featured_image[0])>0) ? $featured_image[0] : gt3_get_theme_option("logo"); ?>"
                                                       class="top_socials share_pinterest"><i class="stand_icon icon-pinterest"></i></a>                                                       
                                                    <div class="clear"></div>
                                                </div>
                                                
                                            </div>
				                        	<?php echo get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder)); ?>
                                            <div class="blog_post_content">
                                                <article class="contentarea">
                                                    <?php
                                                    global $contentAlreadyPrinted;
                                                    if ($contentAlreadyPrinted !== true) {
                                                        the_content(__('Read more!', 'theme_localization'));
                                                    }
                                                    wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages', 'theme_localization') . ': </span>', 'after' => '</div>'));
                                                    ?>
                                                </article>
                                            </div>
                                        </div>
                                        <!--.blog_post_page -->
                                        
                                        <div class="blog_post-footer port_post-footer">
                                            <div class="prev_next_links">
                                                <?php next_post_link('<div class="fleft">%link</div>', 'Previous Post') ?>
                                                <?php previous_post_link('<div class="fright">%link</div>', 'Next Post') ?>                                                    
                                            </div>
                                        
                                            <div class="likes_icons footer_likes">
                                            <?php echo'
                                                <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
                                                <div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_port_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_port_post">
                                                    <i class="stand_icon '.(isset($_COOKIE['like_port_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
                                                    <span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
                                                </div>
                                                '; ?>
                                                <a target="_blank"
                                                   href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"
                                                   class="top_socials share_facebook"><i
                                                        class="stand_icon icon-facebook-square"></i></a>
                                                <a target="_blank"
                                                   href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink(); ?>"
                                                   class="top_socials share_tweet"><i class="stand_icon icon-twitter"></i></a>                                                       
                                                <a target="_blank"
                                                   href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"
                                                   class="top_socials share_gplus"><i class="icon-google-plus-square"></i></a>
                                                <a target="_blank"
                                                   href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo (strlen($featured_image[0])>0) ? $featured_image[0] : gt3_get_theme_option("logo"); ?>"
                                                   class="top_socials share_pinterest"><i class="stand_icon icon-pinterest"></i></a>                                                   
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="dn">
                                    <?php posts_nav_link(); ?>
                                </div>
                                <?php
									if (defined("GT3PBVERSION") && gt3_get_theme_option("related_posts") == "on") {
										if ($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "no-sidebar") {
											$posts_per_line = 4;
										} else {
											$posts_per_line = 3;
										}
		
										echo '<div class="row"><div class="span12 module_cont module_small_padding module_feature_posts single_feature">';
		
										$new_term_list = get_the_terms(get_the_id(), "portcat");
										$echoallterm = '';
										$echoterm = array();
										if (is_array($new_term_list)) {
											foreach ($new_term_list as $term) {
												$echoterm[] = $term->term_id;
											}
										}
										if (is_array($echoterm) && count($echoterm)>0) {
											$post_type_terms = implode(",", $echoterm);
										} else {
											$post_type_terms = "";
										}
		
										echo do_shortcode("[feature_portfolio
										heading_color=''
										heading_size='h3'
										heading_text='Related Works'
										number_of_posts='".$posts_per_line."'
										posts_per_line=".$posts_per_line."
										sorting_type='random'
										related='yes'
										now_open_pageid='".get_the_id()."'
										post_type_terms='".$post_type_terms."'
										post_type='port'][/feature_portfolio]");
										echo '</div></div>';
									}
									if ( comments_open() && gt3_get_theme_option("portfolio_comments") == "enabled" ) {
									?>
									<div class="row">
										<div class="span12">
											<?php comments_template(); ?>
										</div>
									</div>
									<?php } ?>
                            </div>
                            <!-- .contentarea -->
                        </div>
                        <?php get_sidebar('left'); ?>
                    </div>
                    <div class="clear"><!-- ClearFix --></div>
                </div>
                <!-- .fl-container -->
                <?php get_sidebar('right'); ?>
                <div class="clear"><!-- ClearFix --></div>
            </div>
        </div>
        <!-- .container -->
    </div><!-- .content_wrapper -->
    <script>
		jQuery(document).ready(function(){
			jQuery('.pf_output_container').each(function(){
				if (jQuery(this).html() == '') {
					jQuery(this).parents('.blog_post_page').addClass('no_pf');
				} else {
					jQuery(this).parents('.blog_post_page').addClass('has_pf');
				}
			});		
		});
	</script>    
<?php 	
get_footer();
	} else if ($portfolioType == 'fw-portfolio-post'){
		//Fullscreen Type
	wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);
		

	$compile_slides = "";
	$imgi = 1;?>
    <div class="fullscreen-gallery">
	    <div class="fs_grid_gallery">    
	<?php 
	if ($pf == "image" && isset($gt3_theme_pagebuilder['post-formats']['images'])) {
		wp_enqueue_script('gt3_fsGallery_js', get_template_directory_uri() . '/js/fs_gallery.js', array(), false, true);

		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'] !== 'default') {
			$fit_style = $gt3_theme_pagebuilder['sliders']['fullscreen']['fit_style'];
		} else {
			$fit_style = gt3_get_theme_option("default_fit_style");
		}
		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['controls']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'] !== 'default') {
			$controls = $gt3_theme_pagebuilder['sliders']['fullscreen']['controls'];
		} else {
			$controls = gt3_get_theme_option("default_controls");
		}
		if ($controls == 'on') {
			$controls = 'true';
		} else {
			$controls = 'false';
		}
		if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay']) && $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'] !== 'default') {
			$autoplay = $gt3_theme_pagebuilder['sliders']['fullscreen']['autoplay'];
		} else {
			$autoplay = gt3_get_theme_option("default_autoplay");
		}
		if ($autoplay == 'on') {
			$autoplay = 'true';
		} else {
			$autoplay = 'false';
		}

		$interval = gt3_get_theme_option("gallery_interval");
		$sliderCompile = "";				
		if (isset($gt3_theme_pagebuilder['post-formats']['images']) && is_array($gt3_theme_pagebuilder['post-formats']['images'])) {

			$sliderCompile .= '<script>gallery_set = [';
			foreach ($gt3_theme_pagebuilder['post-formats']['images'] as $imageid => $image) {
				$sliderCompile .= '{type: "image", image: "' . wp_get_attachment_url($image['attach_id']) . '", thmb: "'.aq_resize(wp_get_attachment_url($image['attach_id']), "130", "130", true, true, true).'", alt: "", title: "", description: "", titleColor: "", descriptionColor: ""},';
			}

			foreach ($gt3_theme_pagebuilder['post-formats']['images'] as $imageid => $image) {
				$compile_slides .= "<li data-count='".$imgi."' class='slide".$imgi."'><img src='" . aq_resize(wp_get_attachment_url($image['attach_id']), null, "910", true, true, true) . "' alt='image" . $imgi ."'/></li>";
				$imgi++;				
			}
			
			$sliderCompile .= "]		
			jQuery(document).ready(function(){
				jQuery('html').addClass('hasPag');
				jQuery('body').fs_gallery({
					fx: 'fade', /*fade, zoom, slide_left, slide_right, slide_top, slide_bottom*/
					fit: '". $fit_style ."',
					slide_time: ". $interval .", /*This time must be < then time in css*/
					autoplay: 1,
					show_controls: ". $controls .",
					slides: gallery_set
				});
				jQuery('.fs_share').click(function(){
					jQuery('.fs_fadder').removeClass('hided');
					jQuery('.fs_sharing_wrapper').removeClass('hided');
					jQuery('.fs_share_close').removeClass('hided');
				});
				jQuery('.fs_share_close').click(function(){
					jQuery('.fs_fadder').addClass('hided');
					jQuery('.fs_sharing_wrapper').addClass('hided');
					jQuery('.fs_share_close').addClass('hided');
				});
				jQuery('.fs_fadder').click(function(){
					jQuery('.fs_fadder').addClass('hided');
					jQuery('.fs_sharing_wrapper').addClass('hided');
					jQuery('.fs_share_close').addClass('hided');
				});
		
				jQuery('.fs_controls').addClass('up_me');
				jQuery('.fs_title_wrapper ').addClass('up_me');
			
				jQuery('.close_controls').click(function(){
					if (jQuery(this).hasClass('open_controls')) {
						jQuery('.fs_controls').removeClass('hide_me');
						jQuery('.fs_title_wrapper ').removeClass('hide_me');
						jQuery('.fs_thmb_viewport').removeClass('hide_me');
						jQuery('header.main_header').removeClass('hide_me');
						jQuery(this).removeClass('open_controls');
					} else {		
						jQuery('header.main_header').addClass('hide_me');
						jQuery('.fs_controls').addClass('hide_me');
						jQuery('.fs_title_wrapper ').addClass('hide_me');
						jQuery('.fs_thmb_viewport').addClass('hide_me');
						jQuery(this).addClass('open_controls');
					}
				});			
			});
			</script>";
		} 
		echo $sliderCompile;	

		?>
	<div class="fs_title_wrapper">
		<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
            <h1><?php the_title(); ?></h1>
        <?php } ?>                                            
    </div>

    <a href="<?php echo esc_js("javascript:void(0)");?>" class="control_toggle"></a>
    <div class="gallery_post_controls">
        <a href="<?php echo esc_js("javascript:history.back()");?>" class="gallery_post_close"></a>
        <?php previous_post_link('<div class="fright">%link</div>', '') ?>
		<?php next_post_link('<div class="fleft">%link</div>', '') ?>
    </div>
    <script>
		jQuery(document).ready(function(){
			jQuery('.main_header').removeClass('hided');
			jQuery('html').addClass('single-gallery');
			<?php if ($controls == 'false') {
				echo "jQuery('html').addClass('hide_controls');";
			} ?>
			jQuery('.control_toggle').click(function(){
				jQuery('html').toggleClass('hide_controls');
			});
		});	
	</script>    
	<?php 		
	} else if ($pf == "video") { 
		$video_url = $gt3_theme_pagebuilder['post-formats']['videourl'];
		echo "<div class='fullscreen_block fw_background bg_video fs_post_video'>";

		#YOUTUBE
		$is_youtube = substr_count($video_url, "youtu");
		if ($is_youtube > 0) {
			$videoid = substr(strstr($video_url, "="), 1);
			echo "<iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/" . $videoid . "?controls=0&autoplay=1&showinfo=0&modestbranding=1&wmode=opaque&rel=0\" frameborder=\"0\" allowfullscreen></iframe></div>";
		}
		#VIMEO
		$is_vimeo = substr_count($video_url, "vimeo");
		if ($is_vimeo > 0) {
			$videoid = substr(strstr($video_url, "m/"), 2);
			echo "<iframe src=\"http://player.vimeo.com/video/" . $videoid . "?autoplay=1&loop=0\" width=\"100%\" height=\"100%\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>";
		} ?>
        <a href="<?php echo esc_js("javascript:void(0)");?>" class="hide_header"></a>
		<script>
            jQuery(document).ready(function() {
				jQuery('.hide_header').click(function(){
					html.toggleClass('hide_controls');
				});
                jQuery('.fw_background').height(jQuery(window).height());
                jQuery('.main_header').removeClass('hided');
                jQuery('.fullscreen_block').addClass('loaded');
                if (jQuery(window).width() > 1024) {
                    if (jQuery('.bg_video').size() > 0) {
                        if (((jQuery(window).height()+150)/9)*16 > jQuery(window).width()) {				
                            jQuery('iframe').height(jQuery(window).height()+150).width(((jQuery(window).height()+150)/9)*16);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'top' : "-75px", 'margin-top' : '0px'});
                        } else {
                            jQuery('iframe').width(jQuery(window).width()).height(((jQuery(window).width())/16)*9);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'margin-top' : (-1*jQuery('iframe').height()/2)+'px', 'top' : '50%'});
                        }
                    }
                } else if (jQuery(window).width() < 760) {
					jQuery('iframe').width(window_w).height((window_w/16)*9);
					jQuery('.pf_post_info').remove();
					jQuery('.pf_site_wrapper').css('display', 'block').removeClass('pf_hided');
	                jQuery('.fw_background').height(jQuery('iframe').height());
                } else {
                    jQuery('.bg_video').height(jQuery(window).height());
                    jQuery('iframe').height(jQuery(window).height());				
                }
            });
            jQuery(window).load(function() {
                jQuery('.fw_background').height(jQuery(window).height());
                if (jQuery(window).width() > 1024	) {
                    if (jQuery('.bg_video').size() > 0) {
                        if (((jQuery(window).height()+150)/9)*16 > jQuery(window).width()) {
                            jQuery('iframe').height(jQuery(window).height()+150).width(((jQuery(window).height()+150)/9)*16);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'top' : "-75px", 'margin-top' : '0px'});
                        } else {
                            jQuery('iframe').width(jQuery(window).width()).height(((jQuery(window).width())/16)*9);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'margin-top' : (-1*jQuery('iframe').height()/2)+'px', 'top' : '50%'});
                        }
                    }
                } else if (jQuery(window).width() < 760) {
					jQuery('iframe').width(window_w).height((window_w/16)*9);
					jQuery('.pf_post_info').remove();
					jQuery('.pf_site_wrapper').css('display', 'block').removeClass('pf_hided');
	                jQuery('.fw_background').height(jQuery('iframe').height());
                } else {
                    jQuery('.bg_video').height(jQuery(window).height());
                    jQuery('iframe').height(jQuery(window).height());				
                }
            });
            jQuery(window).resize(function() {
                jQuery('.fw_background').height(jQuery(window).height());
                if (jQuery(window).width() > 1024	) {
                    if (jQuery('.bg_video').size() > 0) {
                        if (((jQuery(window).height()+150)/9)*16 > jQuery(window).width()) {
                            jQuery('iframe').height(jQuery(window).height()+150).width(((jQuery(window).height()+150)/9)*16);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'top' : "-75px", 'margin-top' : '0px'});
                        } else {
                            jQuery('iframe').width(jQuery(window).width()).height(((jQuery(window).width())/16)*9);
                            jQuery('iframe').css({'margin-left' : (-1*jQuery('iframe').width()/2)+'px', 'margin-top' : (-1*jQuery('iframe').height()/2)+'px', 'top' : '50%'});
                        }
                    }
                } else if (jQuery(window).width() < 760) {
					jQuery('iframe').width(window_w).height((window_w/16)*9);
					jQuery('.pf_post_info').remove();
					jQuery('.pf_site_wrapper').css('display', 'block').removeClass('pf_hided');
	                jQuery('.fw_background').height(jQuery('iframe').height());
                } else {
                    jQuery('.bg_video').height(jQuery(window).height());
                    jQuery('iframe').height(jQuery(window).height());				
                }
            });
        </script>        
	<?php } ?>	
    
	<?php //CONTENT
	if (get_the_content() !== '' || (isset($gt3_theme_pagebuilder['modules']) && is_array($gt3_theme_pagebuilder['modules']) && count($gt3_theme_pagebuilder['modules'])>0)) { ?>
	        </div>
        </div>
	    <a href="<?php echo esc_js("javascript:void(0)");?>" class="pf_post_info"></a>
        <div class="site_wrapper pf_site_wrapper pf_hided" style="z-index:88;">
            <div class="main_wrapper">        
                <div class="content_wrapper">
                    <div class="container">
                        <div class="content_block row">
                            <div class="fl-container">
                                <div class="row">
                                    <div class="posts-block">
                                    <?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                                        <div class="page_title_block">
                                            <h1 class="title"><?php the_title(); ?></h1>
                                        </div>
                                    <?php } ?>                    
                                        <div class="contentarea">
                                            <?php
                                            the_content(__('Read more!', 'theme_localization'));
                                            wp_link_pages(array('before' => '<div class="page-link">' . __('Pages', 'theme_localization') . ': ', 'after' => '</div>'));
                                            if (gt3_get_theme_option('page_comments') == "enabled") {?>
                                            <hr class="comment_hr"/>
                                            <div class="row">
                                                <div class="span12">
                                                    <?php comments_template(); ?>
                                                </div>
                                            </div>							
                                            <?php }?>							
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .main_wrapper -->
        </div>
        
        <script>
            jQuery(document).ready(function(){
				jQuery('.pf_post_info').click(function(){
					jQuery('.pf_site_wrapper').toggleClass('pf_hided');
					jQuery('html').toggleClass('pf_hided_gallery');
				});
                centerWindow();
                body.addClass('centered_page');
            });
            jQuery(window).load(function(){
                centerWindow();
            });
            jQuery(window).resize(function(){
                centerWindow();
                setTimeout('centerWindow()',500);
                setTimeout('centerWindow()',1000);
            });
            function centerWindow() {
                setTop = (window_h - site_wrapper.height() - parseInt(site_wrapper.css('padding-top')) - parseInt(site_wrapper.css('padding-bottom')))/2;
                setLeft = (window_w - header_w - site_wrapper.width() - parseInt(site_wrapper.css('padding-right')) - parseInt(site_wrapper.css('padding-left')))/2 + header_w;
                if (setTop < 0) {
                    site_wrapper.addClass('fixed');
                    site_wrapper.css({'top' : 0+'px', 'margin-left' : setLeft+'px'});
                } else {
                    site_wrapper.css({'top' : setTop +'px', 'margin-left' : setLeft+'px'});
                    site_wrapper.removeClass('fixed');
                    jQuery('body').removeClass('addPadding');
                }
            }
        </script>         
	<?php 
		}
	?>	
   		<script>
			jQuery(document).ready(function($){
				jQuery('.custom_bg').remove();
			});				
		</script>	
	<?php
    	get_footer('fullscreen'); 		
		//End Of Fullscreen Post
		?>        
	<?php } else {
	//Ribbon
	$compile_slides = "";
	$imgi = 1;?>
    <div class="fullscreen-gallery">
	    <div class="fs_grid_gallery">    
	<?php 
	if ($pf == "image" && isset($gt3_theme_pagebuilder['post-formats']['images'])) {		
		if (isset($gt3_theme_pagebuilder['post-formats']['images']) && is_array($gt3_theme_pagebuilder['post-formats']['images'])) {
			foreach ($gt3_theme_pagebuilder['post-formats']['images'] as $imageid => $image) {
				$compile_slides .= "<li data-count='".$imgi."' class='slide".$imgi."'><div class='slide_wrapper'><img src='" . aq_resize(wp_get_attachment_url($image['attach_id']), null, "910", true, true, true) . "' alt='image" . $imgi ."'/></div></li>";
				$imgi++;				
			}
		} ?>

        <div class="ribbon_wrapper">
            <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_prev"></a><a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_next"></a>
            <div id="ribbon_swipe"></div>
            <div class="ribbon_list_wrapper">
                <ul class="ribbon_list">
                    <?php echo $compile_slides; ?>
                </ul>
            </div>
        </div>
		<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
        <div class="slider_info">
            <div class="slider_data">
                <h2 class="post_title"><?php the_title(); ?></h2>
            </div>
            <div class="post_controls">
                <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                <?php next_post_link('<div class="fleft">%link</div>', ''); ?>
            </div>
        </div>
        <?php } ?>                                                    
            <!-- .fullscreen_content_wrapper -->            
    <script>
		var demension = jQuery('header').width()+parseInt(jQuery('header').css('padding-left'))+parseInt(jQuery('header').css('padding-right'));
		jQuery(document).ready(function($){
			jQuery('#ribbon_swipe').on("swipeleft",function(){
				next_slide();
			});
			jQuery('#ribbon_swipe').on("swiperight",function(){
				prev_slide();
			});			
			jQuery('.btn_prev').click(function(){
				prev_slide();
			});
			jQuery('.btn_next').click(function(){
				next_slide();
			});
			jQuery('.slide1').addClass('currentStep');			
			ribbon_setup();

			jQuery(document.documentElement).keyup(function (event) {
				if ((event.keyCode == 37) || (event.keyCode == 40)) {
					prev_slide();
				} else if ((event.keyCode == 39) || (event.keyCode == 38)) {
					next_slide();
				}
			});
			
			setTimeout("ribbon_setup()",700);			
		});	
		jQuery(window).resize(function($){
			demension = header_w;
			ribbon_setup();
			setTimeout("ribbon_setup()",500);
			setTimeout("ribbon_setup()",1000);			
		});	
		jQuery(window).load(function($){
			ribbon_setup();
			setTimeout("ribbon_setup()",350);
			setTimeout("ribbon_setup()",700);
		});	
		
		function ribbon_setup() {	
			if (window_w > 1024) {
				setHeight = window_h - 7;
				setHeight2 = window_h - jQuery('.slider_info').height() - 14;
				jQuery('.fs_grid_gallery').height(window_h - 1).width(window_w - header_w).css('margin-left', header_w+'px');

				jQuery('.currentStep').removeClass('currentStep');
				jQuery('.slide1').addClass('currentStep');
				jQuery('.num_current').text('1');
				
				jQuery('.num_all').text(jQuery('.ribbon_list li').size());
				jQuery('.ribbon_wrapper').height(setHeight);
				jQuery('.ribbon_list .slide_wrapper').height(setHeight2);
				jQuery('.ribbon_list').height(setHeight2).width(7).css({'left' : 0, 'top' : jQuery('.slider_info').height() + 7+'px'});
				jQuery('.slider_caption').text(jQuery('.currentStep').attr('data-title'));
				jQuery('.ribbon_list').find('li').each(function(){
					jQuery('.ribbon_list').width(jQuery('.ribbon_list').width()+jQuery(this).width());
					jQuery(this).attr('data-offset',jQuery(this).offset().left);
					jQuery(this).width(jQuery(this).find('img').width()+parseInt(jQuery(this).find('.slide_wrapper').css('margin-left')));
				});
				max_step = -1*(jQuery('.ribbon_list').width()-window_w);
			} else {
				jQuery('.ribbon_list').css('padding-top', jQuery('.slider_info').height());
			}
		}
		function prev_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide--;
			if (current_slide < 1) {
				current_slide = jQuery('.ribbon_list').find('li').size();
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset')+demension);
			} else {
				jQuery('.ribbon_list').css('left', max_step-demension);
			}
		}
		function next_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide++;
			if (current_slide > jQuery('.ribbon_list').find('li').size()) {
				current_slide = 1
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset')+demension);
			} else {
				jQuery('.ribbon_list').css('left', max_step-demension);
			}
		}
    </script>
	<?php 		
	} else if ($pf == "video") { ?>
		<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
        <div class="slider_info video_slider_info pf_video_slider_info">
            <div class="slider_data">
                <h2 class="post_title"><?php the_title(); ?></h2>
            </div>
            <div class="post_controls">
                <a href="<?php echo esc_js("javascript:history.back()");?>" class="fw_post_close"></a>
                <?php previous_post_link('<div class="fright">%link</div>', ''); ?>
                <?php next_post_link('<div class="fleft">%link</div>', ''); ?>
            </div>
        </div>
        <?php } ?>                                            

        <div class="ribbon_wrapper">
            <div class="ribbon_list_wrapper ribbon_video_wrapper">
			<?php
                //Video BG
        
                $video_url = $gt3_theme_pagebuilder['post-formats']['videourl'];
                echo "<div class='fw_video_block'>";
        
                #YOUTUBE
                $is_youtube = substr_count($video_url, "youtu");
                if ($is_youtube > 0) {
                    $videoid = substr(strstr($video_url, "="), 1);
                    echo "<iframe width=\"100%\" height=\"100%\" src=\"http://www.youtube.com/embed/" . $videoid . "?controls=0&autoplay=1&showinfo=0&modestbranding=1&wmode=opaque&rel=0\" frameborder=\"0\" allowfullscreen></iframe></div>";
                }
            
                #VIMEO
                $is_vimeo = substr_count($video_url, "vimeo");
                if ($is_vimeo > 0) {
                    $videoid = substr(strstr($video_url, "m/"), 2);
                    echo "<iframe src=\"http://player.vimeo.com/video/" . $videoid . "?autoplay=1&loop=0\" width=\"100%\" height=\"100%\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>";
                }?>
            </div>
        </div>        
            <!-- .fullscreen_content_wrapper -->                
		<script>
            jQuery(document).ready(function($){
                video_setup();
            });	
            jQuery(window).resize(function($){
                video_setup();
            });	
            jQuery(window).load(function($){
                video_setup();
            });	
            
            function video_setup() {
				if (window_w > 1024) {
					setHeight2 = window_h - jQuery('.slider_info').height(),
					setHeight = window_h - jQuery('.slider_info').height()-7;
					jQuery('.fs_grid_gallery').height(window_h - 1).width(window_w - header_w).css('margin-left', header_w+'px');
					jQuery('.ribbon_wrapper').height(setHeight);
					jQuery('.fw_video_block').height(setHeight2-7);
					jQuery('.fw_video_block').width(((setHeight2-7)/9)*16);
				} else if (window_w < 760) {
					jQuery('.ribbon_wrapper').find('iframe').height(window_h - jQuery('.pf_video_slider_info').height() - header.height());
				} else {
					jQuery('.fw_video_block').find('iframe').height(window_h - jQuery('.pf_video_slider_info').height()).width(window_w - header_w).css({
						'margin': '0',
						'position': 'static'
					});
					jQuery('.fs_grid_gallery').height(window_h);
				}
			}
        </script>
	<?php } ?>
        
    	</div>
    </div>
	<script>
        jQuery(document).ready(function($){
			jQuery('.custom_bg').remove();
			if (jQuery('.fl-container').size() > 0) {
				jQuery('.fw_post_info').click(function(){
					jQuery('html, body').stop().animate({
						scrollTop: jQuery(jQuery('.content_wrapper')).offset().top-10
					}, 500);					
				});
			} else {
				jQuery('.fw_post_info').hide();
			}			
        });			
    </script>
	<?php	
	get_footer('fullscreen');  ?>    
    <?php 
	}
	?>
<?php } else {
	get_header('fullscreen');
	echo "<div class='fixed_bg' style='background-image:url(".gt3_get_theme_option('bg_img').")'></div>";
?>
    <div class="pp_block">
        <h1 class="pp_title"><?php  _e('THIS CONTENT IS PASSWORD PROTECTED', 'theme_localization') ?></h1>
        <div class="pp_wrapper">
            <?php the_content(); ?>
        </div>
    </div>
    <div class="global_center_trigger"></div>	
    <script>
		jQuery(document).ready(function(){
			jQuery('.post-password-form').find('label').find('input').attr('placeholder', 'Enter The Password...');
		});
	</script>
<?php 
	get_footer('fullscreen');
} ?>