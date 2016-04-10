<?php

#Upload images
add_action('wp_ajax_mix_ajax_post_action', 'mix_theme_upload_images');
function mix_theme_upload_images()
{
    if (is_admin()) {
        $save_type = $_POST['type'];

        if ($save_type == 'upload') {

            $clickedID = esc_attr($_POST['data']);
            $filename = $_FILES[$clickedID];
            $filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

            $override['test_form'] = false;
            $override['action'] = 'wp_handle_upload';
            $uploaded_file = wp_handle_upload($filename, $override);
            $upload_tracking[] = $clickedID;
            gt3_update_theme_option($clickedID, $uploaded_file['url']);
            if (!empty($uploaded_file['error'])) {
                echo 'Upload Error: ' . $uploaded_file['error'];
            } else {
                echo esc_url($uploaded_file['url']);
            }
        }
    }

    die();
}

#Upload images
add_action('wp_ajax_gt3_get_blog_posts', 'gt3_get_blog_posts');
add_action('wp_ajax_nopriv_gt3_get_blog_posts', 'gt3_get_blog_posts');
function gt3_get_blog_posts()
{
    $setPad = esc_attr($_REQUEST['set_pad']);
    if ($_REQUEST['template_name'] == "fw_blog_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
            $all_likes = gt3pb_get_option("likes");
            $gt3_theme_pagebuilder = get_post_meta(get_the_ID(), "pagebuilder", true);

            if (get_the_category()) $categories = get_the_category();
            $post_categ = '';
            $separator = ', ';
            if ($categories) {
                foreach ($categories as $category) {
                    $post_categ = $post_categ . '<a href="' . get_category_link($category->term_id) . '">' . $category->cat_name . '</a>' . $separator;
                }
            }

            ?>
            <div <?php post_class("blogpost_preview_fw"); ?>>
                <div class="fw_preview_wrapper featured_items newAddedPosts">
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
                        <h4 class="blogpost_title"><a
                                href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
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
                            <h3 class="blogpost_title"><a href="' . get_permalink() . '"><?php get_the_title() ?></a>
                            </h3>

                            <div class="listing_meta">
                                <span><i
                                        class="stand_icon icon-calendar-o"></i><?php echo get_the_time("F d, Y") ?></span>
                                <span><i class="icon-folder"></i><?php echo trim($post_categ, ', ') ?></span>
                                <span><i class="icon-comments"></i><a
                                        href="<?php echo get_comments_link() ?>"><?php echo get_comments_number(get_the_ID()) ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_query();
    }

    /* PORTFOLIO ISOTOPE */
    if ($_REQUEST['template_name'] == "port_masonry_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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
                        ' ' => ', ',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_masonry2_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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
                        ' ' => ', ',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>

        <?php endwhile;
        wp_reset_query();
    }

    /* PORTFOLIO DEFAULT */
    if ($_REQUEST['template_name'] == "port_masonry_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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

            ?>


        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_masonry2_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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

            ?>


        <?php endwhile;
        wp_reset_query();
    }

    /* G R I D   P O R T F O L I O */
    /* PORTFOLIO ISOTOPE */
    if ($_REQUEST['template_name'] == "port_grid_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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
                        ' ' => ', ',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>
        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_grid2_isotope") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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
                        ' ' => ', ',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            } else {
                $tempname = 'Uncategorized';
            }
            ?>

        <?php endwhile;
        wp_reset_query();
    }

    /* PORTFOLIO DEFAULT */
    if ($_REQUEST['template_name'] == "port_grid_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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

            ?>


        <?php endwhile;
        wp_reset_query();
    }

    if ($_REQUEST['template_name'] == "port_grid2_template") {
        $wp_query_get_blog_posts = new WP_Query();
        $args = array(
            'post_type' => esc_attr($_REQUEST['post_type']),
            'offset' => absint($_REQUEST['posts_already_showed']),
            'post_status' => 'publish',
            'cat' => esc_attr($_REQUEST['categories']),
            'posts_per_page' => absint($_REQUEST['posts_count'])
        );
        $wp_query_get_blog_posts->query($args);
        while ($wp_query_get_blog_posts->have_posts()) : $wp_query_get_blog_posts->the_post();
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

            ?>


        <?php endwhile;
        wp_reset_query();
    }

    die();
}

#Get last slide ID
add_action('wp_ajax_get_unused_id_ajax', 'get_unused_id_ajax');
if (!function_exists('get_unused_id_ajax')) {
    function get_unused_id_ajax()
    {
        $lastid = gt3_get_theme_option("last_slide_id");
        if ($lastid < 3) {
            $lastid = 2;
        }
        $lastid++;

        $mystring = home_url();
        $findme = 'gt3themes';
        $pos = strpos($mystring, $findme);

        if ($pos === false) {
            echo $lastid;
        } else {
            echo str_replace(array("/", "-", "_"), "", substr(wp_get_theme()->get('ThemeURI'), -4, 3)) . date("d") . date("m") . $lastid;
        }

        gt3_update_theme_option("last_slide_id", $lastid);

        die();
    }
}


add_action('wp_ajax_add_like_post', 'gt3_add_like_post');
add_action('wp_ajax_nopriv_add_like_post', 'gt3_add_like_post');
function gt3_add_like_post()
{
    $post_id = absint($_POST['post_id']);
    $post_likes = (get_post_meta($post_id, "post_likes", true) > 0 ? get_post_meta($post_id, "post_likes", true) : "0");
    $new_likes = absint($post_likes) + 1;
    update_post_meta($post_id, "post_likes", $new_likes);
    echo $new_likes;
    die();
}

#Load portfolio works
add_action('wp_ajax_get_portfolio_works', 'get_portfolio_works');
add_action('wp_ajax_nopriv_get_portfolio_works', 'get_portfolio_works');
if (!function_exists('get_portfolio_works')) {
    function get_portfolio_works() {	
	    $setPad = esc_attr($_REQUEST['set_pad']);	
		
        $html_template = esc_attr($_POST['template_name']);
        $now_open_works = absint($_POST['posts_already_showed']);
        $works_per_load = absint($_POST['posts_count']);
        $category = esc_attr($_POST['categories']);
		$post_type_field = esc_attr($_POST['post_type_field']);
        $wp_query = new WP_Query();
        $args = array(
            'post_type' => 'port',
            'order' => 'DESC',
            'post_status' => 'publish',
            'offset' => $now_open_works,
            'posts_per_page' => $works_per_load,
        );

        if (strlen($category) > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portcat',
                    'field' => $post_type_field,
                    'terms' => ($post_type_field == "slug" ? $category : explode(",", $category) )
                )
            );
        }

        $wp_query->query($args);
        //$i = 1;
		
        while ($wp_query->have_posts()) : $wp_query->the_post();
            $pf = get_post_format();
            if (empty($pf)) $pf = "text";
            $pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());

            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'single-post-thumbnail');
            if (strlen($featured_image[0]) < 1) {
                $featured_image[0] = IMGURL . "/core/your_image_goes_here.jpg";
            }

            if (isset($pagebuilder['settings']['external_link']) && strlen($pagebuilder['settings']['external_link']) > 0) {
                $linkToTheWork = $pagebuilder['settings']['external_link'];
                $target = "target='_blank'";
            } else {
                $linkToTheWork = get_permalink();
                $target = "";
            }

            if (isset($pagebuilder['settings']['time_spent']) && strlen($pagebuilder['settings']['time_spent']) > 0) {
                $time_spent_value = $pagebuilder['settings']['time_spent'];
                $time_spent_html = '<div class="portfolio_descr_time">' . ((get_theme_option("translator_status") == "enable") ? get_text("translator_time_spent") : __('Time spent', 'theme_localization')) . ': <span>' . $time_spent_value . '</span></div>';
            } else {
                $time_spent_value = '';
                $time_spent_html = '';
            }

            if (!isset($echoallterm)) {
                $echoallterm = '';
            }
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => '-',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
                }
            }

            #Portfolio grid
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
			$portCateg = '';
            $new_term_list = get_the_terms(get_the_id(), "portcat");
            if (is_array($new_term_list)) {
                foreach ($new_term_list as $term) {
                    $tempname = strtr($term->name, array(
                        ' ' => ', ',
                    ));
                    $echoallterm .= strtolower($tempname) . " ";
                    $echoterm = $term->name;
					$portCateg .= $term->name . ", ";
                }
				$portCateg = substr($portCateg, 0, -2);
            } else {
                $tempname = 'Uncategorized';
				$portCateg = 'Uncategorized';
            }
            			
			/* M A S O N R Y   P O R T F O L I O */ 			
		    if ($_REQUEST['template_name'] == "port_masonry_isotope") { ?>
                <div <?php post_class("blogpost_preview_fw loading anim_el newLoaded fw-portPreview element " . $echoallterm); ?>
                    data-category="<?php echo $echoallterm ?>">
                    <div class="fw-portPreview-wrapper" style="padding:0 40px 40px 0">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">
    
                            <div class="fw-portPreview-fadder"></div>
                            <div class="fw-portPreview-content">
                                <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
    
                                <div class="block_likes">
                                    <div class="fw-portPreview-views">
                                        <i class="stand_icon icon-eye"></i>
                                        <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                    </div>
                                    <div
                                        class="fw-portPreview-likes <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                        data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                        <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                        <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
			<?php }
		    if ($_REQUEST['template_name'] == "port_masonry_template") { ?>
                <div <?php post_class("blogpost_preview_fw newLoaded fw-portPreview"); ?>>
                    <div class="fw-portPreview-wrapper loading anim_el" style="padding:0 40px 40px 0">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">
    
                            <div class="fw-portPreview-fadder"></div>
                            <div class="fw-portPreview-content">
                                <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
    
                                <div class="block_likes">
                                    <div class="fw-portPreview-views">
                                        <i class="stand_icon icon-eye"></i>
                                        <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                    </div>
                                    <div
                                        class="fw-portPreview-likes <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                        data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                        <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                        <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>				
            <?php 
			}
		    if ($_REQUEST['template_name'] == "port_masonry2_isotope") { ?>
                <div <?php post_class("blogpost_preview_fw loading anim_el newLoaded fw-portPreview element " . $echoallterm); ?>
                    data-category="<?php echo $echoallterm ?>">
                    <div class="fw-portPreview-wrapper featured_items"
                         style="padding:0 <?php echo $setPad; ?> <?php echo $setPad; ?> 0">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                <img alt="" width="540" height=""
                                     src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>"/>
    
                                <div class="featured_item_fadder"></div>
                            </a>
    
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
			<?php }
		    if ($_REQUEST['template_name'] == "port_masonry2_template") { ?>
                <div <?php post_class("blogpost_preview_fw newLoaded fw-portPreview"); ?>>
                    <div class="fw-portPreview-wrapper loading anim_el featured_items"
                         style="padding:0 <?php echo $setPad; ?> <?php echo $setPad; ?> 0">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                <img alt="" width="540" height=""
                                     src="<?php echo aq_resize($featured_image[0], "540", "", true, true, true); ?>"/>
    
                                <div class="featured_item_fadder"></div>
                            </a>
    
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>				
            <?php 
			}

			/* G R I D   P O R T F O L I O */
			if ($_REQUEST['template_name'] == "port_grid_isotope") { ?>
                <div <?php post_class("blogpost_preview_fw loading mas_style1 newLoaded fw-portPreview element " . $echoallterm); ?>
                    data-category="<?php echo $echoallterm ?>">
                    <div class="fw-portPreview-wrapper" style="padding:0 40px 40px 0">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">
    
                            <div class="fw-portPreview-fadder"></div>
                            <div class="fw-portPreview-content">
                                <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
    
                                <div class="block_likes">
                                    <div class="fw-portPreview-views">
                                        <i class="stand_icon icon-eye"></i>
                                        <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                    </div>
                                    <div
                                        class="fw-portPreview-likes <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                        data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                        <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                        <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
			<?php }
		    if ($_REQUEST['template_name'] == "port_grid_template") { ?>
                <div <?php post_class("blogpost_preview_fw newLoaded fw-portPreview"); ?>>
                    <div class="fw-portPreview-wrapper loading mas_style1" style="padding:0 40px 40px 0">
                        <a href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                            <img src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>" alt=""
                                 class="fw_featured_image" width="540">
    
                            <div class="fw-portPreview-fadder"></div>
                            <div class="fw-portPreview-content">
                                <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
    
                                <div class="block_likes">
                                    <div class="fw-portPreview-views">
                                        <i class="stand_icon icon-eye"></i>
                                        <span><?php echo(get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                    </div>
                                    <div
                                        class="fw-portPreview-likes <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                        data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                        <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                        <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
			<?php }
			if ($_REQUEST['template_name'] == "port_grid2_isotope") { ?>
                <div <?php post_class("blogpost_preview_fw loading anim_el newLoaded fw-portPreview element " . $echoallterm); ?>
                    data-category="<?php echo $echoallterm ?>">
                    <div class="fw-portPreview-wrapper featured_items"
                         style="padding:0 <?php echo $setPad; ?> <?php echo $setPad; ?> 0">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                <img alt="" width="540" height=""
                                     src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>"/>
    
                                <div class="featured_item_fadder"></div>
                            </a>
    
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
			<?php }
		    if ($_REQUEST['template_name'] == "port_grid2_template") { ?>
                <div <?php post_class("blogpost_preview_fw newLoaded fw-portPreview"); ?>>
                    <div class="fw-portPreview-wrapper loading anim_el featured_items"
                         style="padding:0 <?php echo $setPad; ?> <?php echo $setPad; ?> 0">
                        <div class="img_block wrapped_img">
                            <a class="featured_ico_link" href="<?php echo $linkToTheWork; ?>" <?php echo $target; ?>>
                                <img alt="" width="540" height=""
                                     src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>"/>
    
                                <div class="featured_item_fadder"></div>
                            </a>
    
                            <div
                                class="gallery_likes gallery_likes_add <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "already_liked" : ""); ?>"
                                data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_port">
                                <i class="stand_icon <?php echo(isset($_COOKIE['like_port' . get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                <span><?php echo((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()] > 0) ? $all_likes[get_the_ID()] : 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
			<?php }
			
			
            #END

            //$i++;
            //unset($echoallterm, $pf);
        endwhile;

        die();
    }
}

#Ajax import xml
add_action('wp_ajax_ajax_import_dump', 'ajax_import_dump');
if (!function_exists('ajax_import_dump')) {
    function ajax_import_dump()
    {
        if (is_admin()) {
            if (!defined('WP_LOAD_IMPORTERS')) {
                define('WP_LOAD_IMPORTERS', true);
            }

            require_once(TEMPLATEPATH . '/core/xml-importer/importer.php');

            try {
                ob_start();
                $importer = new WP_Import();
                $importer->import(TEMPLATEPATH . '/core/xml-importer/import.xml');
                ob_clean();
            } catch (Exception $e) {
                die(json_encode(array(
                    'message' => $e->getMessage()
                )));
            }
            die(json_encode(array(
                'message' => 'Data was imported successfully'
            )));
        }
    }
}

?>