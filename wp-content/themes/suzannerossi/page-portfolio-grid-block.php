<?php 
/*
Template Name: Portfolio - Grid Style
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
			$post_type_field = "id";
            $post_type_terms = array();
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
	    <div class="fs_blog_module fw_port_module" style="overflow:hidden">
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
					<div class="fw-portPreview-wrapper mas_style1" style="padding:0 40px 40px 0">
                            <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                <img src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>" alt="" class="fw_featured_image" width="540">
                                <div class="fw-portPreview-fadder"></div>
                                <div class="fw-portPreview-content">
                                    <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
                                    <div class="block_likes">
                                        <div class="fw-portPreview-views">
                                            <i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                        </div>                            
                                        <div class="fw-portPreview-likes <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                            <i class="stand_icon <?php echo (isset($_COOKIE['like_port'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                            <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                        </div>
                                    </div>
                                </div>                                
                            </a>
                    </div>
                </div>      
			<?php endwhile; wp_reset_query();?>
        </div>
		<?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') { ?>
            <a href="<?php echo esc_js("javascript:void(0)");?>" class="load_more_works btn_normal btn_type2 shortcode_button"><i class="icon-arrow-down"></i><?php _e('Load more works', 'theme_localization') ?></a>
            
        <?php }?>        
	</div>
    <script>

        var posts_already_showed = <?php gt3_the_theme_option('fw_port_per_page'); ?>,
			<?php if (isset($selected_categories) && strlen($selected_categories) > 0) {
				echo 'categories = "'. $selected_categories .'"';
			} else {
				echo 'categories = ""';
			}?>

	<?php if (isset($gt3_theme_pagebuilder['portfolio']['port_type']) && $gt3_theme_pagebuilder['portfolio']['port_type'] == 'port_isotope') {?>
        function get_works() {
            gt3_get_isotope_posts("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_grid_isotope", ".fs_blog_module", categories, '0px', '<?php echo $post_type_field; ?>');
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
            gt3_get_portfolio("port", <?php gt3_the_theme_option('fw_port_per_page'); ?>, posts_already_showed, "port_grid_template", ".fs_blog_module", categories, '0px', '<?php echo $post_type_field; ?>');
            posts_already_showed = posts_already_showed + <?php gt3_the_theme_option('fw_port_per_page'); ?>;
        }	
        jQuery(document).ready(function () {
            jQuery(window).on('scroll', scrolling);
        });
	<?php } ?>

		jQuery(document).ready(function($){
			jQuery('.custom_bg').remove();
			port_setup();
		});	
		jQuery(window).load(function($){
			port_setup();
		});	
		jQuery(window).resize(function($){
			port_setup();
		});
		function port_setup() {
			jQuery('.fw-portPreview-content').each(function(){
				jQuery(this).css('margin-top', -1*jQuery(this).height()/2+'px');
			});				
		}		
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