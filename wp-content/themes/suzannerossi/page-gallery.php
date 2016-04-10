<?php
/*
Template Name: Gallery
*/
?>
<?php if( have_rows('background_images') ): ?>
	<?php if( get_field('random_images') ): ?>
		<ul class="slides custom_bg img_bg random">
	<?php else: ?>
		<ul class="slides custom_bg img_bg">	
	<?php endif; ?>
	
	<?php $i = 0;
		while( have_rows('background_images') ): the_row(); 
		$i++;
		// vars
		$image = get_sub_field('image');
		$caption = get_sub_field('caption');
		$title = get_sub_field('image_title');
		?>
		<li class="slide" style="background-image: url(<?php echo $image; ?>);">
			<?php if($title): ?>
			<div class="caption">
				<div class="photo-title"><?php echo $title; ?></div>
			<?php if($caption): ?>	
				<div class="photo-caption"><?php echo $caption; ?></div>		
			<?php endif; ?>			
			</div>	
			<?php endif; ?>
		</li>
	<?php endwhile; ?>
	</ul>
	<?php if($i > 1): ?>
	  <div class="click-btn">
		  <div class="home-prev-photo btn">‹</div>							  
		  <div class="home-next-photo btn">›</div>							  
	  </div>
	<?php endif; ?>
<?php endif; ?>
<?php 
if ( !post_password_required() ) {
get_header(); the_post();
$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());
?>
<div class="content_wrapper">
	<div class="container">
        <div class="content_block row <?php echo esc_attr($gt3_theme_pagebuilder['settings']['layout-sidebars']) ?>">
            <div class="fl-container <?php echo(($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "right-sidebar") ? "hasRS" : ""); ?>">
                <div class="row">
                    <div class="posts-block <?php echo($gt3_theme_pagebuilder['settings']['layout-sidebars'] == "left-sidebar" ? "hasLS" : ""); ?>">
					<?php if (!isset($gt3_theme_pagebuilder['settings']['show_title']) || $gt3_theme_pagebuilder['settings']['show_title'] !== "no") { ?>
                        <div class="page_title_block">
							<h1 class="title"><?php the_title(); ?></h1>
                        </div>
                    <?php } ?>                    
						<div class="site-main container_16">
							<div class="inner">
							        <ul id="galleries">
									<?php 
									$loops = get_field('galleries_order');
									foreach( $loops as $loop): // variable must be called $post (IMPORTANT) 
										$image = get_field('gallery' , $loop->ID);	
										$thumb = wp_get_attachment_image( get_post_thumbnail_id($loop->ID), 'gallery-thumb');
										//print_r($thumb);
										?>
						                <li class="gallery-list">
						                	<a href="<?php the_permalink($loop->ID); ?>">
							                	<?php echo $thumb;?>
							                	<div class="gallery-title"><?php echo $loop->post_title; ?></div>
															</a>
						                </li>
									<?php endforeach; 
									echo '</ul>';
									?>
										
								</div>
								<div class="clear"></div>
							</div>		
						</div>
                    </div>
                    <?php get_sidebar('left'); ?>
                </div>
            </div>
            <?php get_sidebar('right'); ?>
        </div>
    </div>
</div>

<script>
	jQuery(document).ready(function(){
		setUpWindow();
	});
	jQuery(window).load(function(){
		setUpWindow();
	});
	jQuery(window).resize(function(){
		setUpWindow();
		setTimeout('setUpWindow()',500);
		setTimeout('setUpWindow()',1000);
	});
	function setUpWindow() {
		main_wrapper.css('min-height', window_h-parseInt(site_wrapper.css('padding-top')) - parseInt(site_wrapper.css('padding-bottom'))+'px');
	}
</script>

<?php 
	get_footer();
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