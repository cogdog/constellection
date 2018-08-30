<?php
/**
 * Template part for displaying page content for page-add-star.php
**/
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'rebalance' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<div class="entry-meta"><span class="edit-link">',
				'</span></div><!-- .entry-meta -->'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->