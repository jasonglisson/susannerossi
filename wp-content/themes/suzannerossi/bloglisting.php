<?php
wp_enqueue_script('gt3_cookie_js', get_template_directory_uri() . '/js/jquery.cookie.js', array(), false, true);
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
if (strlen($featured_image[0])>0) {
	$pf_url = $featured_image[0];
} else {
	$pf_url = gt3_get_theme_option("logo");
}

$gt3_theme_pagebuilder = gt3_get_theme_pagebuilder(get_the_ID());

if(get_the_category()) $categories = get_the_category();
$post_categ = '';
$separator = ', ';
if ($categories) {
    foreach($categories as $category) {
        $post_categ = $post_categ .'<a href="'.get_category_link( $category->term_id ).'">'.$category->cat_name.'</a>'.$separator;
    }
}

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
	if (!isset($pf)) {
		$compile = '';
	}

	$compile .= '
	<div class="blog_post_preview"><div class="post_preview_wrapper">';
	// Top Elements
				$compile .= '<div class="blog_content">						
							<div class="blogpreview_top">
								<h3 class="blogpost_title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>
								<div class="listing_meta">
									<span><i class="stand_icon icon-calendar-o"></i>'. get_the_time("F d, Y") .'</span>
									<span><i class="icon-user"></i><a href="'.get_author_posts_url( get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a></span>									
									<span><i class="icon-folder"></i>'.trim($post_categ, ', ').'</span>
									'.$post_tags_compile.'
									<span><i class="icon-comments"></i><a href="' . get_comments_link() . '">'. get_comments_number(get_the_ID()) .' comments</a></span>
								</div>
								<div class="likes_icons">
			                        <div class="post-views"><i class="stand_icon icon-eye"></i> <span>'. (get_post_meta(get_the_ID(), "post_views", true) > 0 ? get_post_meta(get_the_ID(), "post_views", true) : "0") .'</span></div>
									<div class="gallery_likes gallery_likes_add '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "already_liked" : "").'" data-attachid="'.get_the_ID().'" data-modify="like_post">
										<i class="stand_icon '.(isset($_COOKIE['like_post'.get_the_ID()]) ? "icon-heart" : "icon-heart-o").'"></i>
										<span>'.((isset($all_likes[get_the_ID()]) && $all_likes[get_the_ID()]>0) ? $all_likes[get_the_ID()] : 0).'</span>
									</div>

									<a target="_blank"
									   href="http://www.facebook.com/share.php?u='. get_permalink() .'"
									   class="top_socials share_facebook"><i class="stand_icon icon-facebook-square"></i></a>
									<a target="_blank"
									   href="https://twitter.com/intent/tweet?text='. get_the_title() .'&amp;url='. get_permalink().'"
									   class="top_socials share_tweet"><i class="stand_icon icon-twitter"></i></a>
									<a target="_blank" href="https://plus.google.com/share?url='. get_permalink() .'"
									   class="top_socials share_gplus"><i class="icon-google-plus-square"></i></a>
									<a target="_blank"
									   href="http://pinterest.com/pin/create/button/?url='. get_permalink() .'&media='. $pf_url .'"
									   class="top_socials share_pinterest"><i class="stand_icon icon-pinterest"></i></a>									   
								</div>
							</div>';
				//Featured Image
				$compile .= get_pf_type_output(array("pf" => get_post_format(), "gt3_theme_pagebuilder" => $gt3_theme_pagebuilder));
				
				$compile .= '<article class="contentarea">
								' . ((strlen(get_the_excerpt()) > 0) ? get_the_excerpt() : get_the_content())   . ' <a href="' . get_permalink() . '" class="gt3_readmore">Read More</a>
							</article>
					</div>
				</div></div><!--.blog_post_preview -->';
	
	echo $compile;

	$GLOBALS['showOnlyOneTimeJS']['BlogListing'] = "
		<script>
			jQuery(document).ready(function($) {
			jQuery('.pf_output_container').each(function(){
				if (jQuery(this).html() == '') {
					jQuery(this).parents('.post_preview_wrapper').addClass('no_pf');
				} else {
					jQuery(this).parents('.post_preview_wrapper').addClass('has_pf');
				}
			});
			});
		</script>
	";
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
