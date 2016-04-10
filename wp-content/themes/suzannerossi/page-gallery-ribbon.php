
<?php 
/*
Template Name: Gallery - Ribbon
*/
if ( !post_password_required() ) {
get_header('fullscreen');
the_post();

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
$pf = get_post_format();			
wp_enqueue_script('gt3_prettyPhoto_js', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array(), false, true);
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
wp_enqueue_script('gt3_swipe_js', get_template_directory_uri() . '/js/jquery.event.swipe.js', array(), false, true);
$all_likes = gt3pb_get_option("likes");
$post_views = (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0");
update_post_meta(get_the_ID(), "post_views", (int)$post_views + 1);
?>
    <div class="fullscreen-gallery hided">
	    <div class="fs_grid_gallery">
		<?php 
            $compile_slides = "";
        ?>
        <?php
        if (isset($gt3_theme_pagebuilder['sliders']['fullscreen']['slides']) && is_array($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'])) {        
			$imgi = 1;
            foreach ($gt3_theme_pagebuilder['sliders']['fullscreen']['slides'] as $imageid => $image) {
				if (isset($image['title']['value']) && strlen($image['title']['value'])>0) {$photoTitle = ' : '.$image['title']['value'];} else {$photoTitle = " ";}
				if (isset($image['title']['value']) && strlen($image['title']['value'])>0) {$photoAlt = $image['title']['value'];} else {$photoAlt = " ";}
				if (isset($image['caption']['value']) && strlen($image['caption']['value'])>0) {$photoCaption  = $image['caption']['value'];} else {$photoCaption = " ";}				
				$compile_slides .= "<li data-count='".$imgi."' data-title='". $photoTitle ."' data-caption='". $photoCaption ."' class='slide".$imgi."'><div class='slide_wrapper'><img src='" . aq_resize(wp_get_attachment_url($image['attach_id']), null, "910", true, true, true) . "' alt='" . $photoAlt ."'/></div></li>";
				$imgi++;
				?>   
				<?php }
	        }?>
            
            <div class="ribbon_wrapper">
                <a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_prev"></a><a href="<?php echo esc_js("javascript:void(0)");?>" class="btn_next"></a>
                <div id="ribbon_swipe"></div>
                <div class="ribbon_list_wrapper">
                    <ul class="ribbon_list">
                        <?php echo $compile_slides; ?>
                    </ul>
                </div>
            </div>
            <?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
            <div class="slider_info">
            	<div class="slider_data">
                    <h2 class="slider_title"><?php the_title(); ?></h2><h2 class="slider_caption"></h2>
                </div>
            </div>
            <?php } ?>
            <!-- .fullscreen_content_wrapper -->            
    	</div>
    </div>
    <script>
		var demension = jQuery('header').width()+parseInt(jQuery('header').css('padding-left'))+parseInt(jQuery('header').css('padding-right'));
		jQuery(document).ready(function($){
			jQuery('.custom_bg').remove();
			jQuery('#ribbon_swipe').on("swipeleft",function(){
				next_slide();
			});
			jQuery('#ribbon_swipe').on("swiperight",function(){
				prev_slide();
			});			
			jQuery('.btn_prev').click(function(){
				prev_slide();
			});
			jQuery('.btn_next').click(function(){
				next_slide();
			});
			jQuery(document.documentElement).keyup(function (event) {
				if ((event.keyCode == 37) || (event.keyCode == 40)) {
					prev_slide();
				} else if ((event.keyCode == 39) || (event.keyCode == 38)) {
					next_slide();
				}
			});
			
			jQuery('.slide1').addClass('currentStep');
			jQuery('.slider_caption').text(jQuery('.currentStep').attr('data-title'));			
			ribbon_setup();			
			setTimeout("ribbon_setup()",700);			
		});	
		jQuery(window).resize(function($){
			demension = header_w;
			ribbon_setup();
			setTimeout("ribbon_setup()",500);
			setTimeout("ribbon_setup()",1000);			
		});	
		jQuery(window).load(function($){
			ribbon_setup();
			setTimeout("ribbon_setup()",350);
			setTimeout("ribbon_setup()",700);
		});	
		
		function ribbon_setup() {	
			if (window_w > 1024) {
				setHeight = window_h - 7;
				setHeight2 = window_h - jQuery('.slider_info').height() - 14;
				jQuery('.fs_grid_gallery').height(window_h - 1).width(window_w - header_w).css('margin-left', header_w+'px');

				jQuery('.currentStep').removeClass('currentStep');
				jQuery('.slide1').addClass('currentStep');
				jQuery('.num_current').text('1');
				
				jQuery('.num_all').text(jQuery('.ribbon_list li').size());
				jQuery('.ribbon_wrapper').height(setHeight);
				jQuery('.ribbon_list .slide_wrapper').height(setHeight2);
				jQuery('.ribbon_list').height(setHeight2).width(7).css({'left' : 0, 'top' : jQuery('.slider_info').height() + 7+'px'});
				jQuery('.slider_caption').text(jQuery('.currentStep').attr('data-title'));
				jQuery('.ribbon_list').find('li').each(function(){
					jQuery('.ribbon_list').width(jQuery('.ribbon_list').width()+jQuery(this).width());
					jQuery(this).attr('data-offset',jQuery(this).offset().left);
					jQuery(this).width(jQuery(this).find('img').width()+parseInt(jQuery(this).find('.slide_wrapper').css('margin-left')));
				});
				max_step = -1*(jQuery('.ribbon_list').width()-window_w);
			} else {
				jQuery('.ribbon_list').css('padding-top', jQuery('.slider_info').height());
			}
		}
		function prev_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide--;
			if (current_slide < 1) {
				current_slide = jQuery('.ribbon_list').find('li').size();
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			jQuery('.slider_caption').text(jQuery('.currentStep').attr('data-title'));
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset')+demension);
			} else {
				jQuery('.ribbon_list').css('left', max_step-demension);
			}
		}
		function next_slide() {
			current_slide = parseInt(jQuery('.currentStep').attr('data-count'));
			current_slide++;
			if (current_slide > jQuery('.ribbon_list').find('li').size()) {
				current_slide = 1
			}
			jQuery('.currentStep').removeClass('currentStep');
			jQuery('.num_current').text(current_slide);
			jQuery('.slide'+current_slide).addClass('currentStep');
			jQuery('.slider_caption').text(jQuery('.currentStep').attr('data-title'));
			if (-1*jQuery('.slide'+current_slide).attr('data-offset') > max_step) {
				jQuery('.ribbon_list').css('left', -1*jQuery('.slide'+current_slide).attr('data-offset')+demension);
			} else {
				jQuery('.ribbon_list').css('left', max_step-demension);
			}
		}
    </script>
<?php get_footer('fullscreen'); 
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