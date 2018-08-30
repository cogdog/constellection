<?php
/**
 * Template part for displaying single constellations.
 *
 */
 
 global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">Constellation #' . get_the_ID() . ': ' , '</h1>' ); ?>

		<div class="entry-meta">
			<?php constellection_entry_meta(); ?>
			
			<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
		</div><!-- .entry-meta -->

		<?php the_post_navigation( array(
			'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous Constellation', 'rebalance' ) . '</span>',
			'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next Constellation', 'rebalance' ) . '</span>'
			)); ?>
	</header><!-- .entry-header -->
	
	
	<?php if ( constellection_json_exists() ) : // we have data ?>
	
		<div id="universe" class="post-hero-image clear-fix">

		<?php
			
			$args = array(
				'starnodes' => $post->nodejson,
				'starlinks' => $post->linkjson
			);

			$output = constellection_template( get_stylesheet_directory() . '/universe/constellation.php', $args );
		
			print $output;	
			?>
		</div>

	

	<?php elseif ( rebalance_has_post_thumbnail() ) : ?>
	
	<div class="post-hero-image clear-fix">
		<figure class="entry-image">
			<?php the_post_thumbnail( 'full' ); ?>
		</figure>
	</div><!-- .post-hero-image -->
	
	<?php endif?>

	<div class="entry-content">
	
		<div class="pull-right">
			<p><strong>This constellation is made of the following stars:</strong></p> 
			
			<?php echo get_stars_for_constellation( $post->star_list );?>

		</div>
	
		<?php the_content(); ?>
		
		
		<?php if ( function_exists( 'echo_crp' ) ) :?>
			<h2>Similar Constellations</h2>
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
		'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous Constellation', 'rebalance' ) . '</span> <span class="meta-nav-title">%title</span>',
		'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next Constellation', 'rebalance' ) . '</span> <span class="meta-nav-title">%title</span> '
	));

	/**
	 * Display the author meta
	 */
	rebalance_author_bio(); ?>
