<?php
/*
Template Name: Gallery - Albums
*/
if ( !post_password_required() ) {
    get_header('fullscreen');
    the_post();

    $gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
    $pf = get_post_format();
    wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);

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
        } else {
            $selected_categories = "";
        }
        $post_type_terms = array();
        if (isset($selected_categories) && strlen($selected_categories) > 0) {
            $post_type_terms = explode(",", $selected_categories);
            if (count($post_type_terms) > 0) {
                $args = array(
                    'post_type' => 'gallery',
                    'order' => 'DESC',
                    'paged' => $paged,
                    'posts_per_page' => -1
                );
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'gallerycat',
                        'field' => 'id',
                        'terms' => $post_type_terms
                    )
                );
            }
        } else {
            $args = array(
                'post_type' => 'gallery',
                'order' => 'DESC',
                'paged' => $paged,
                'posts_per_page' => -1
            );
        }
        $wp_query_in_shortcodes = new WP_Query();

        if (isset($_GET['slug']) && strlen($_GET['slug']) > 0) {
            $post_type_terms = $_GET['slug'];
            if (count($post_type_terms) > 0) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'gallerycat',
                        'field' => 'slug',
                        'terms' => $post_type_terms
                    )
                );
            }
        }
        ?>
        <div class="fs_blog_module fw_port_module">
            <?php
            $wp_query_in_shortcodes->query($args);
            while ($wp_query_in_shortcodes->have_posts()) : $wp_query_in_shortcodes->the_post();
                $all_likes = gt3pb_get_option("likes");
                $gt3_theme_post = get_plugin_pagebuilder(get_the_ID());
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                $pf = get_post_format();
                $echoallterm = '';
                $new_term_list = get_the_terms(get_the_id(), "gallerycat");
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
                <div <?php post_class("blogpost_preview_fw fw-portPreview"); ?>>
                    <div class="fw-portPreview-wrapper mas_style1" style="padding:0 40px 40px 0">
                        <a href="<?php echo get_permalink(); ?>">
                            <img src="<?php echo aq_resize($featured_image[0], "540", "540", true, true, true); ?>" alt="" class="fw_featured_image" width="540">
                            <div class="fw-portPreview-fadder"></div>
                            <div class="fw-portPreview-content">
                                <h2 class="fw-portPreview-title"><?php the_title(); ?></h2>
                                <div class="block_likes">
                                    <div class="fw-portPreview-views">
                                        <i class="stand_icon icon-eye"></i> <span><?php echo (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0"); ?></span>
                                    </div>
                                    <div class="fw-portPreview-likes <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "already_liked" : ""); ?>" data-attachid="<?php echo get_the_ID(); ?>" data-modify="like_album">
                                        <i class="stand_icon <?php echo (isset($_COOKIE['like_album'.get_the_ID()]) ? "icon-heart" : "icon-heart-o"); ?>"></i>
                                        <span><?php echo ((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endwhile; wp_reset_query();?>
            <div class="clear"></div>
        </div>
    </div>
    <script>

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
    <?php
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