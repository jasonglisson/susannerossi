<?php 
	$gt3page_settings = gt3_get_theme_pagebuilder(@get_the_ID());
	
?>
<!DOCTYPE html>
<html <?php language_attributes();?> class="fullscreen">
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
    <?php echo((gt3_get_theme_option("responsive") == "on") ? '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' : ''); ?>
    <link rel="shortcut icon" href="<?php echo gt3_get_theme_option('favicon'); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?php echo gt3_get_theme_option('apple_touch_57'); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo gt3_get_theme_option('apple_touch_72'); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo gt3_get_theme_option('apple_touch_114'); ?>">
    <title><?php wp_title(); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <script type="text/javascript">
        var gt3_ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php echo gt3_get_if_strlen(gt3_get_theme_option("custom_css"), "<style>", "</style>") . gt3_get_if_strlen(gt3_get_theme_option("code_before_head"));
	wp_enqueue_script('gt3_chart_js', get_template_directory_uri() . '/js/chart.js', array(), false, true);
    globalJsMessage::getInstance()->render();
    wp_head(); ?>
</head>

<body <?php body_class(array(gt3_the_pb_custom_bg_and_color(gt3_get_theme_pagebuilder(@get_the_ID()), array("classes_for_body" => true)))); ?>>
    <header class="main_header">
    	<div class="header_scroll">
            <div class="header_wrapper">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo"><img src="<?php gt3_the_theme_option("logo"); ?>" alt=""  width="<?php gt3_the_theme_option("header_logo_standart_width"); ?>" height="<?php gt3_the_theme_option("header_logo_standart_height"); ?>" class="logo_def"><img src="<?php gt3_the_theme_option("logo_retina"); ?>" alt="" width="<?php gt3_the_theme_option("header_logo_standart_width"); ?>" height="<?php gt3_the_theme_option("header_logo_standart_height"); ?>" class="logo_retina"></a>
                <!-- WPML Code Start -->
                <?php /*do_action('icl_language_selector');*/ ?>
                <!-- WPML Code End -->
                <nav>
                    <?php wp_nav_menu(array('theme_location' => 'main_menu', 'menu_class' => 'menu', 'depth' => '3', 'walker' => new gt3_menu_walker($showtitles = false))); ?>
                </nav>
                <?php 
				$now_post_type = get_post_type();
				if ($now_post_type == "page" && (get_page_template_slug() == "page-portfolio-masonry.php" || get_page_template_slug() == "page-portfolio-masonry-block.php" || get_page_template_slug() == "page-portfolio-grid.php" || get_page_template_slug() == "page-portfolio-grid-block.php" || get_page_template_slug() == "page-albums.php")) { ?>
                <div class="header_filter">                	
					<?php
						if (isset($gt3page_settings['settings']['cat_ids']) && (is_array($gt3page_settings['settings']['cat_ids']))) {
							$compile_cats = array();
							foreach ($gt3page_settings['settings']['cat_ids'] as $catkey => $catvalue) {
								array_push($compile_cats, $catkey);
							}
							$selected_categories = implode(",", $compile_cats);
						}
						else {
							$selected_categories = "";
						}
						$post_type_terms = array();
						if (isset($selected_categories) && strlen($selected_categories) > 0) {
							$post_type_terms = explode(",", $selected_categories);
						}
					
                        #Filter
                        if (!isset($gt3page_settings['fs_portfolio']['filter']) || $gt3page_settings['fs_portfolio']['filter'] == 'on') {
							echo '<a href="'. esc_js("javascript:void(0)") .'>" class="filter_toggler">'. __('Filter', 'theme_localization') .'</a>';
							$compile = '';
							if (get_page_template_slug() == "page-albums.php") {
								$compile .= showAsideGalleryCats($post_type_terms);		
							} else {
								$compile .= showAsidePortCats($post_type_terms);							
							}
                            echo $compile;
                        }
                    ?>
                    <script>
						jQuery(document).ready(function ($) {
							jQuery('.filter_toggler').click(function(){
								jQuery('.optionset').slideToggle(300);
								jQuery('.filter_toggler').toggleClass('toggled');
							});
							if (window_w < 760) {
								jQuery('.optionset').slideToggle(1);
								jQuery('.filter_toggler').toggleClass('toggled');							
							}
						});
					</script>             	
                </div>
 				<?php } ?>
                <div class="widget_area">
                    <?php get_sidebar('footer'); ?>
                </div>
            </div><!-- Header Wrapper -->
            <div class="footer_wrapper">            
                <div class="socials_wrapper">
                    <?php echo gt3_show_social_icons(array(
                        array(
                            "uniqid" => "social_facebook",
                            "class" => "ico_social_facebook",
                            "title" => "Facebook",
                            "target" => "_blank",
                        ),					
                        array(
                            "uniqid" => "social_pinterest",
                            "class" => "ico_social_pinterest",
                            "title" => "Pinterest",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_twitter",
                            "class" => "ico_social_twitter",
                            "title" => "Twitter",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_instagram",
                            "class" => "ico_social_instagram",
                            "title" => "Instagram",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_tumblr",
                            "class" => "ico_social_tumblr",
                            "title" => "Tumblr",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_flickr",
                            "class" => "ico_social_flickr",
                            "title" => "Flickr",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_youtube",
                            "class" => "ico_social_youtube",
                            "title" => "Youtube",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_dribbble",
                            "class" => "ico_social_dribbble",
                            "title" => "Dribbble",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_gplus",
                            "class" => "ico_social_gplus",
                            "title" => "Google+",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_vimeo",
                            "class" => "ico_social_vimeo",
                            "title" => "Vimeo",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_delicious",
                            "class" => "ico_social_delicious",
                            "title" => "Delicious",
                            "target" => "_blank",
                        ),
                        array(
                            "uniqid" => "social_linked",
                            "class" => "ico_social_linked",
                            "title" => "Linked In",
                            "target" => "_blank",
                        )
                    ));
                    ?>
                </div>
                <div class="copyright"><?php gt3_the_theme_option("copyright"); ?></div>
            </div><!-- footer_wrapper -->
            </div>
	</header>
