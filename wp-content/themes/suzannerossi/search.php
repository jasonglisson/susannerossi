<?php get_header('centered');
#Emulate default settings for page without personal ID
$gt3_theme_pagebuilder = gt3_get_default_pb_settings();
$gt3_current_page_sidebar = $gt3_theme_pagebuilder['settings']['layout-sidebars'];
?>

    <div class="content_wrapper">
        <div class="container">
            <div class="content_block <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?> row">
            <div class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                <div class="row">
                    <div class="posts-block <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
                            <div class="contentarea">
                                <?php
                                echo '<div class="row-fluid"><div class="span12 module_cont module_blog">';

                                global $paged;
                                $foundSomething = false;

                                if ($paged < 1) {
                                    $args = array(
                                        'numberposts' => -1,
                                        'post_type' => 'any',
                                        'meta_query' => array(
                                            array(
                                                'key' => 'pagebuilder',
                                                'value' => get_search_query(),
                                                'compare' => 'LIKE',
                                                'type' => 'CHAR'
                                            )
                                        )
                                    );
                                    $query = new WP_Query($args);
                                    while ($query->have_posts()) : $query->the_post();									
										if(get_the_tags() !== '') {
											$posttags = get_the_tags();
										
										}
										if ($posttags) {
											$post_tags = '';
											$post_tags_compile = '<span class="preview_meta_tags">tags:';
											foreach($posttags as $tag) {
												$post_tags = $post_tags . '<a href="?tag='.$tag->slug.'">'.$tag->name .'</a>'. ', ';
											}
											$post_tags_compile .= ' '.trim($post_tags, ', ').'</span>';
										} else {
											$post_tags_compile = '';
										}
										$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');										
										?>
                                        <div <?php post_class("blog_post_preview search_page"); ?>><div class="post_preview_wrapper">
                                            <div class="blog_content">
	                                            <div class="blogpreview_top">
	                                                <h3 class="blogpost_title" style="border:none"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <div class="listing_meta">
														<span><i class="stand_icon icon-calendar-o"></i><?php the_time("F d, Y") ?></span>
                                                        <span><i class="icon-user"></i><?php the_author_meta('display_name'); ?></span>
                                                        <span><i class="icon-comments"></i><a href="<?php echo get_comments_link() ?>"><?php echo get_comments_number(get_the_ID()) ?> <?php _e('comments', 'theme_localization'); ?></a></span>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div></div>
                                        <?php
                                        $foundSomething = true;
                                    endwhile;
                                    wp_reset_query();
                                }

                                $defaults = array('numberposts' => 10, 'post_type' => 'any', 'post_status' => 'publish', 'post_password' => '', 'suppress_filters' => false, 's' => get_search_query(), 'paged' => $paged);
                                $query = http_build_query($defaults);
                                $posts = get_posts($query);

                                foreach ($posts as $post) {
                                    setup_postdata($post);
										$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');										
										?>
                                        <div <?php post_class("blog_post_preview search_page"); ?>><div class="post_preview_wrapper">
                                            <div class="blog_content">
	                                            <div class="blogpreview_top">
	                                                <h3 class="blogpost_title" style="border:none"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                    <div class="listing_meta">
														<span><i class="stand_icon icon-calendar-o"></i><?php the_time("F d, Y") ?></span>
                                                        <span><i class="icon-user"></i><?php the_author_meta('display_name'); ?></span>
                                                        <span><i class="icon-comments"></i><a href="<?php echo get_comments_link() ?>"><?php echo get_comments_number(get_the_ID()) ?> <?php _e('comments', 'theme_localization'); ?></a></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div></div>
                                    <?php
									
									$foundSomething = true;
                                }
                                echo gt3_get_theme_pagination();

                                if ($foundSomething == false) {
                                    ?>
                                    <div class="block404" style="width:100%; text-align: center;">
                                        <h1 class="search_oops"><?php echo __('Oops!', 'theme_localization'); ?> <?php echo __('Not Found!', 'theme_localization'); ?></h1>

                                        <div class="search_form_wrap">
                                            <form name="search_field" method="get" action="<?php echo home_url(); ?>"
                                                  class="search_form" style="margin-top: 14px; margin-bottom: 40px;">
                                                <input type="text" name="s"
                                                       value=""
                                                       placeholder="<?php _e('Search the site...', 'theme_localization'); ?>"
                                                       class="field_search">
                                            </form>
                                        </div>
                                    </div>
                                <?php
                                }

                                echo '</div><div class="clear"></div></div>';
                                ?>
                            </div>
                        </div>
                        <?php get_sidebar('left'); ?>
                    </div>
                </div>
                <?php get_sidebar('right'); ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>

	<script>
        jQuery(document).ready(function(){
            centerWindow();
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
            if (setTop < 0) {
                site_wrapper.addClass('fixed');
                site_wrapper.css('top', 0+'px');
            } else {
                site_wrapper.css('top', setTop +'px');
                site_wrapper.removeClass('fixed');
                jQuery('body').removeClass('addPadding');
            }
        }
    </script>

<?php get_footer(); ?>