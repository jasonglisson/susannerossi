<?php get_header('fullscreen');?>
    <div class="wrapper404">
        <div class="container404">
        	<h1 class="title404"><?php echo __('404', 'theme_localization'); ?></h1>
            <form name="search_field" method="get" action="<?php echo home_url(); ?>" class="search_form search404">
                <input type="text" name="s" value="" class="field_search" placeholder="<?php _e('Search the Site', 'theme_localization'); ?>">
                <a href="<?php echo esc_js("javascript:document.search_field.submit()");?>" class="search_button"><?php _e('Search', 'theme_localization'); ?></a>
            </form>
            <div class="clear"></div>
        </div>        
    </div>
    <div class="custom_bg404 img_bg" style="background-image: url(<?php echo gt3_get_theme_option('bg_404'); ?>); ?>;"></div>
    <script>
		var wrapper404 = jQuery('.wrapper404');
		jQuery(document).ready(function(){
			centerWindow();
		});
		jQuery(window).resize(function(){
			setTimeout('centerWindow()',500);
			setTimeout('centerWindow()',1000);			
		});
		function centerWindow() {
			setTop = (window_h - wrapper404.height())/2;
			wrapper404.css('top', setTop +'px');
			wrapper404.removeClass('fixed');
		}
	</script>
<?php get_footer('fullscreen'); ?>