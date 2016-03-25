<?php
/* Template Name: Blog-post */

get_header();

global $post, $wpgrade_private_post, $page_section_idx, $header_height;

//some global variables that we use in our page sections
$is_gmap                = false;
$footer_needs_big_waves = false;
$page_section_idx       = 0;

if ( post_password_required() && ! $wpgrade_private_post['allowed'] ) {
	// password protection
	get_template_part( 'templates/password-request-form' );

} else {

	while ( have_posts() ) : the_post();

		get_template_part( 'templates/page/header' );

		$classes = "article--page  article--main article-custom" ;

		$down_arrow_style = wpgrade::option('down_arrow_style');
		if ( $page_section_idx == 1 && $header_height == 'full-height' && $down_arrow_style == 'bubble' ) {
			$classes .= " article--arrow";
		}	
		$border_style = get_post_meta( wpgrade::lang_page_id( get_the_ID() ), wpgrade::prefix() . 'page_border_style', true );
		if ( ! empty( $border_style ) ) {
			$classes .= ' border-' . $border_style;
		}

		if ( ! empty( $post->post_content ) ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
				<section>
					<div class="col-8">
						<div class="container-fulid">
							<section class="page__content  js-post-gallery  cf">
								<?php 
									$args = array( 
											'post_type' => 'blog-post',
											'posts_per_page' => 20000000000000000 , 
											'order'=>'asc'
											);
	                 				$the_query = new WP_Query($args);
								?>
								<?php $count = 0; ?>
								<?php $count_inner = 0; ?>
								<?php if ( $the_query->have_posts() ) : ?>
								    <div id="isotope-list">
									    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
											<?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
												
												<?php if($count % 3 == 0): ?>
													<?php if($count_inner < 1): ?>
														<a href="<?php echo get_permalink(); ?>">
															<div class="grid-item grid-col-6">
																<h3><?php the_title();?></h3>
																<small>
																	<?php 
																	$terms = get_the_terms( $post->ID , 'portfolio_categories');
																	foreach( $terms as $term ) {
																		print $term->slug ;
																		echo ", ";
																		unset($term);
																	} ?>
																</small>
																<div class="image-container" style="background-image: url('<?php echo $url; ?>');">
																	<div class="linear-background"></div>
																</div>
															</div>
														</a>
														<?php $count_inner++; ?>
													<?php else: ?>
														<a href="<?php echo get_permalink(); ?>">
															<div class="grid-item grid-col-6 float-right">
																<h3><?php the_title();?></h3>
																<small>
																	<?php 
																	$terms = get_the_terms( $post->ID , 'portfolio_categories');
																	foreach( $terms as $term ) {
																		print $term->slug ;
																		echo ", ";
																		unset($term);
																	} ?>
																</small>
																<div class="image-container" style="background-image: url('<?php echo $url; ?>');">
																	<div class="linear-background"></div>
																</div>
															</div>
														</a>
														<?php $count_inner = 0; ?>
													<?php endif;?>
														<?php $count++; ?>
												<?php else: ?>
													<a href="<?php echo get_permalink(); ?>">
														<div class="grid-item grid-col-3">
															<h3><?php the_title();?></h3>
															<small>
																<?php 
																$terms = get_the_terms( $post->ID , 'portfolio_categories');
																foreach( $terms as $term ) {
																	print $term->slug ;
																	echo ", ";
																	unset($term);
																} ?>
															</small>
															<div class="image-container" style="background-image: url('<?php echo $url; ?>');">
																<div class="linear-background"></div>
															</div>
														</div>
													</a>
													<?php $count++; ?>
												<?php endif; ?>
									    <?php endwhile;  ?>
								    </div> 
								<?php endif; ?>
							</section>
						</div>
					</div>
					<div class="col-4"></div>
				</section>
			</article>
		<?php endif;
		get_template_part( 'templates/subpages' );
	endwhile;

} // close if password protection

get_footer();
