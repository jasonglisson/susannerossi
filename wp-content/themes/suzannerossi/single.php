<?php 
if ( !post_password_required() ) {
/* LOAD PAGE BUILDER ARRAY */
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];

$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);

get_header();
the_post();
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
$all_likes = gt3pb_get_option("likes");
?>
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
                                                    <span><i class="stand_icon icon-calendar-o"></i><?php the_time("F d, Y") ?></span>
                                                    <span><i class="icon-user"></i><?php the_author_meta('display_name'); ?></span>
                                                    <span><i class="icon-folder"></i><?php the_category(', '); ?></span>
                                                    <?php the_tags("<span><i class='icon-tag'></i>", ', ', '</span>'); ?>
                                                    <span><i class="icon-comments"></i><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID())?> <?php _e('comments', 'theme_localization'); ?></a></span>                                                    
                                                </div>                                        

                                                <div class="likes_icons">
                                                <?php echo'
                                                    <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
                                                    <div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
                                                        <i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
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
                                        
                                        <div class="blogpost_user_meta">
	                                        <h4><?php _e('About the Author', 'theme_localization'); ?></h4>
                                        	<div class="user_meta_content">
                                                <div class="author-ava">
                                                    <?php echo get_avatar(get_the_author_meta('ID'), 130); ?>
                                                </div>
                                                <h5 class="author-name"><?php the_author_posts_link(); ?></h5>
                                                <div class="author-description"><?php the_author_meta('description'); ?></div>
                                            </div>
                                        </div>

                                        <div class="blog_post-footer">
                                            <div class="prev_next_links">
                                                <?php next_post_link('<div class="fleft">%link</div>', 'Previous Post') ?>
                                                <?php previous_post_link('<div class="fright">%link</div>', 'Next Post') ?>                                                    
                                            </div>
                                        
                                            <div class="likes_icons footer_likes">
                                            <?php echo'
                                                <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
                                                <div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
                                                    <i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
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
	
										echo '<div class="row"><div class="span12 module_cont module_small_padding featured_items single_feature featured_posts">';
										echo do_shortcode("[feature_posts
									heading_color=''
									heading_size='h4'
									heading_text='". __('Related Works', 'theme_localization') ."'
									number_of_posts=" . $posts_per_line . "
									posts_per_line=" . $posts_per_line . "
									sorting_type='random'
									related='yes'
									post_type='post'][/feature_posts]");
										echo '</div></div>';
									}
                                ?>
                                <div class="row">
                                    <div class="span12">
                                        <?php comments_template(); ?>
                                    </div>
                                </div>
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
            jQuery('.gallery_likes_add').bind('click',function(){
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
			
			jQuery('.pf_output_container').each(function(){
				if (jQuery(this).html() == '') {
					jQuery(this).parents('.blog_post_page').addClass('no_pf');
				} else {
					jQuery(this).parents('.blog_post_page').addClass('has_pf');
				}
			});		
		});
	</script>
<?php get_footer();
} else {
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