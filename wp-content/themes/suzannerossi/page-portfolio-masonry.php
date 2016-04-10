<?php 
/*
Template Name: Portfolio - Masonry Style 2
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
wp_enqueue_script('gt3_masonry_js', get_template_directory_uri() . '/js/masonry.min.js', array(), false, true);

if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') {
	wp_enqueue_script('gt3_isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array(), false, true);
	wp_enqueue_script('gt3_isotope_sorting', get_template_directory_uri() . '/js/sorting.js', array(), false, true);
}
if (isset($gt3_theme_pagebuilder['fs_portfolio']['interval'])) {
	$setPad = $gt3_theme_pagebuilder['fs_portfolio']['interval'];
} else {
	$setPad = 0;
}
?>
    <div class="fullscreen_block hided">
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
			}
            $post_type_terms = array();
			$post_type_field = "id";
			if (isset($selected_categories) && strlen($selected_categories) > 0) {
				$post_type_terms = explode(",", $selected_categories);
				$post_type_field = "id";
			}
			
			$wp_query_in_shortcodes = new WP_Query();
            $args = array(
                'post_type' => 'port',
                'order' => 'DESC',
                'paged' => $paged,
                'posts_per_page' => gt3_get_theme_option('fw_port_per_page')
            );

            if (isset($_GET['slug']) && strlen($_GET['slug']) > 0) {
                $post_type_terms = $_GET['slug'];
				$selected_categories = $_GET['slug'];
				$post_type_field = "slug";
            }
            if (count($post_type_terms) > 0) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'portcat',
                        'field' => $post_type_field,
                        'terms' => $post_type_terms
                    )
                );
            }					
		?>
        <div class="fs_blog_module fw_port_module2 is_masonry" style="overflow:hidden; margin-top:<?php echo $setPad; ?>; margin-left:<?php echo $setPad; ?>;">
		<?php 
	        $wp_query_in_shortcodes->query($args);			
	        while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();
				$all_likes = gt3pb_get_option("likes");
				$gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
				$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
				$pf = get_post_format();
                $target = (isset($gt3_theme_post['settings']['new_window']) && $gt3_theme_post['settings']['new_window'] == "on" ? "target='_blank'" : "");
				if (isset($gt3_theme_post['page_settings']['portfolio']['work_link']) && strlen($gt3_theme_post['page_settings']['portfolio']['work_link']) > 0) {
					$linkToTheWork = esc_url($gt3_theme_post['page_settings']['portfolio']['work_link']);
				} else {
					$linkToTheWork = get_permalink();
				}
				$echoallterm = '';
				$new_term_list = get_the_terms(get_the_id(), "portcat");
                if (is_array($new_term_list)) {
                    foreach ($new_term_list as $term) {
                        $tempname = strtr($term->name, array(
                            ' ' => '-',
                        ));
                        $echoallterm .= strtolower($tempname) . " ";
                        $echoterm = $term->name;
                    }
                } else {
                    $tempname = 'Uncategorized';
                }							
			?>
            <?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') { ?>
				<div <?php post_class("blogpost_preview_fw fw-portPreview element ". $echoallterm); ?> data-category="<?php echo $echoallterm ?>">
            <?php } else { ?>
				<div <?php post_class("blogpost_preview_fw fw-portPreview"); ?>>
			<?php } ?>
					<div class="fw-portPreview-wrapper featured_items" style="padding:0 <?php echo $setPad; ?> <?php echo $setPad; ?> 0">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            	<img alt="" width="540" height="" src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" />
                                <div class="featured_item_fadder"></div>
							</a>
                            <div class="gallery_likes gallery_likes_add <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>                    
                    </div>
                </div>      
			<?php endwhile; wp_reset_query();?>
        </div>
		<?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') { ?>
            <a href="<?php echo esc_js("javascript:void(0)");?>" class="load_more_works btn_normal btn_type2 shortcode_button"><i class="icon-arrow-down"></i><?php _e('Load more works', 'theme_localization') ?></a>
            
        <?php }?>        
	</div>
    <script>
		jQuery(document).ready(function($){
			jQuery('.custom_bg').remove();
		});	
        var posts_already_showed = <?php gt3_the_theme_option('fw_port_per_page'); ?>,
			<?php if (isset($selected_categories) && strlen($selected_categories) > 0) {
				echo 'categories = "'. $selected_categories .'"';
			} else {
				echo 'categories = ""';
			}?>

	<?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') {?>
        function get_works() {
            gt3_get_isotope_posts("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_masonry2_isotope", ".fs_blog_module", categories, '<?php echo $setPad; ?>', '<?php echo $post_type_field; ?>');
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_port_per_page'); ?>;
        }
        jQuery(document).ready(function () {
            jQuery('.load_more_works').click(function(){
				get_works();
			});
        });
	<?php } else { ?>
        function get_works() {
            <?php if (gt3_get_theme_option("demo_server") == "true") { ?> if (posts_already_showed > 24) {posts_already_showed = 0;} <?php } ?>
            gt3_get_portfolio("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_masonry2_template", ".fs_blog_module", categories, '<?php echo $setPad; ?>', '<?php echo $post_type_field; ?>');
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_port_per_page'); ?>;
        }	
        jQuery(document).ready(function () {
            jQuery(window).on('scroll', scrolling);
        });
        jQuery(window).load(function () {
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry();",1000);
        });
        jQuery(window).resize(function () {
			jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry();",1000);
        });
		jQuery(document).ready(function($){
            jQuery('.is_masonry').masonry();
			setTimeout("jQuery('.is_masonry').masonry();",1000);
		});		
	<?php } ?>

    </script>    
	<?php 
		$GLOBALS['showOnlyOneTimeJS']['gallery_likes'] = "
		<script>
			jQuery(document).ready(function($) {
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
		";		
	?>
    
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