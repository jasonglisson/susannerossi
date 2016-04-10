<?php 
/*
Template Name: Fullscreen Blog
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();
wp_enqueue_script('gt3_masonry_js', get_template_directory_uri() . '/js/masonry.min.js', array(), false, true);
wp_enqueue_script('gt3_nivo_js', get_template_directory_uri() . '/js/nivo.js', array(), false, true);			
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
?>
   <div class="fullscreen_block hasPreloader">
	    <div class="fs_blog_module is_masonry this_is_blog">
		<?php 
			global $wp_query_in_shortcodes, $paged;
			if(empty($paged)){
				$paged = (get_query_var('page')) ? get_query_var('page') : 1;
			}
			if (isset($gt3_theme_pagebuilder['settings']['cat_ids']) && (is_array($gt3_theme_pagebuilder['settings']['cat_ids']))) {
				$compile_cats = array();
				foreach ($gt3_theme_pagebuilder['settings']['cat_ids'] as $catkey => $catvalue) {
					array_push($compile_cats, $catkey);
				}
				$selected_categories = implode(",", $compile_cats);
			} else {
				$selected_categories = "";
			}
			$wp_query_in_shortcodes = new WP_Query();
			$args = array(
				'post_type' => 'post',
				'paged' => $paged,
				'cat' => $selected_categories,				
                'post_status' => 'publish',
				'posts_per_page' => gt3_get_theme_option('fw_posts_per_page')
			);			
	        $wp_query_in_shortcodes->query($args);
	        while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();
				$all_likes = gt3pb_get_option("likes");
				$gt3_theme_pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);
	
				if(get_the_category()) $categories = get_the_category();
				$post_categ = '';
				$separator = ', ';
				if ($categories) {						
					foreach($categories as $category) {
						$post_categ = $post_categ .'<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
					}
				}
			
			?>
				<div <?php post_class("blogpost_preview_fw"); ?>>
					<div class="fw_preview_wrapper featured_items">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo get_permalink(); ?>">
		                    	<?php echo get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder, "width" => '585', "height" => '', "fw_post" => true)); ?>
                                <div class="featured_item_fadder"></div>
							</a>
                            <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                        <div class="fw_blog_content">
	                        <h4 class="blogpost_title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
                            <article class="contentarea">
                                <?php 
									if (has_excerpt()) {
										echo get_the_excerpt(); 
									} else {
										echo smarty_modifier_truncate(get_the_content(), 120);; 
									}
								?>
                            </article>
							<div class="blogpreview_top">
								<h3 class="blogpost_title"><a href="' . get_permalink() . '"><?php get_the_title() ?></a></h3>
								<div class="listing_meta">
									<span><i class="stand_icon icon-calendar-o"></i><?php echo get_the_time("F d, Y") ?></span>
									<span><i class="icon-folder"></i><?php echo trim($post_categ, ', ') ?></span>
									<span><i class="icon-comments"></i><a href="<?php echo get_comments_link() ?>"><?php echo get_comments_number(get_the_ID()) ?></a></span>
								</div>
							</div>
                        </div>
					</div>
				</div>            
			<?php endwhile; wp_reset_query();?>
        </div>
	</div>
		<script>
        jQuery(window).resize(function () {
			jQuery('.is_masonry').masonry();
        });
		jQuery(document).ready(function($){
			jQuery('.custom_bg').remove();
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry();",1000);
			setTimeout("jQuery('.is_masonry').masonry();",2000);			

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
            jQuery('.nivoSlider').each(function(){
                jQuery(this).nivoSlider({
                    directionNav: false,
                    controlNav: true,
                    effect:'fade',
                    pauseTime:4000,
                    slices: 1
                });
            });			
		});
    </script>
	<script>
        var posts_already_showed = <?php gt3_the_theme_option('fw_posts_per_page'); ?>,
			<?php if (isset($selected_categories) && strlen($selected_categories) > 0) {
				echo 'categories = "'. $selected_categories .'";';
			} else {
				echo 'categories = "";';
			}?>

        function get_works() {
            <?php if (gt3_get_theme_option("demo_server") == "true") { ?> if (posts_already_showed > 15) {posts_already_showed = 0;} <?php } ?>
            gt3_get_blog_posts("post", <?php gt3_the_theme_option('fw_posts_per_page'); ?>, posts_already_showed, "fw_blog_template", ".fs_blog_module", categories, '0px');
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_posts_per_page'); ?>;
        }

        jQuery(document).ready(function () {
            jQuery(window).on('scroll', scrolling);
        });
    </script>    
<?php get_footer('fullwidth'); 
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