<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rebalance
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div id="infinite-wrap">

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'card' );
					?>

				<?php endwhile; ?>
				
								<?php
								
								if ( is_post_type_archive('constellation') ) {
									the_posts_navigation( array(
										'prev_text'          => esc_html__( 'Older constellations', 'rebalance' ),
										'next_text'          => esc_html__( 'Newer constellations', 'rebalance' ),
										'screen_reader_text' => esc_html__( 'Constellation navigation', 'rebalance' ),
									) );
								} elseif ( is_post_type_archive('star-item') ) {
								
									the_posts_navigation( array(
										'prev_text'          => esc_html__( 'Older star items', 'rebalance' ),
										'next_text'          => esc_html__( 'Newer star items', 'rebalance' ),
										'screen_reader_text' => esc_html__( 'Star Item navigation', 'rebalance' ),
									) );
								} else {
									the_posts_navigation();
								}
								?>

			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
