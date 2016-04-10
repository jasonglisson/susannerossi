<?php 
/* Template Name: Vertically Stretched Page */
if ( !post_password_required() ) {
get_header(); the_post();
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
?>
<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { 
	$TitleClass = "hasTitle";
} else {
	$TitleClass = "noTitle";
}
?>
<div class="content_wrapper">
	<div class="container">
        <div class="content_block row no-sidebar">
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

<script>
	jQuery(document).ready(function(){
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