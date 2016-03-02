<?php defined( 'ABSPATH' ) or die;
/*  for PRO users! -  @var stdClass $post */
/*  for PRO users! -  @var mixed $more */
?>

<div>
	<a class="btn btn-primary excerpt-read-more"
		href="<?php echo get_permalink( wpgrade::lang_post_id( $post->ID ) ) ?>"
		title="<?php echo __( 'Read more about', 'rosa_txtd' ) . ' ' . get_the_title( wpgrade::lang_post_id( $post->ID ) ) ?>">

		<?php echo __( 'Read more', 'rosa_txtd' ) ?>

	</a>
</div>