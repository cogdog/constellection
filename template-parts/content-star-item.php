<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rebalance
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php constellection_entry_meta(); ?>
			
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
		</div><!-- .entry-meta -->

		<?php the_post_navigation( array(
			'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous Star Item', 'rebalance' ) . '</span>',
			'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next Star Item', 'rebalance' ) . '</span>'
			)); ?>
	</header><!-- .entry-header -->

	<?php if ( rebalance_has_post_thumbnail() ) { ?>
	<div class="post-hero-image clear-fix">
		<figure class="entry-image">
			<?php the_post_thumbnail( 'full' ); ?>
		</figure>
	</div><!-- .post-hero-image -->
	<?php } ?>

	<div class="entry-content">
		<?php the_content(); ?>
				
		<?php if ( function_exists( 'echo_crp' ) ) :?>
			<h2>Similar Star Items</h2>
			<?php echo do_shortcode('[crp heading="0"]');?>

		<?php endif?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-meta">
			
			<?php constellection_entry_footer(); ?>
		</div>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

<?php
	/**
	 * Display the post navigation
	 */
	the_post_navigation( array(
		'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous', 'rebalance' ) . '</span> <span class="meta-nav-title">%title</span>',
		'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next', 'rebalance' ) . '</span> <span class="meta-nav-title">%title</span> '
	));

	/**
	 * Display the author meta
	 */
	rebalance_author_bio(); ?>
