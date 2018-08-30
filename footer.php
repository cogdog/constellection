<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rebalance
 */
?>

			<footer id="colophon" class="site-footer" role="contentinfo">
			
					
			
				<div class="site-info">
				<?php if (get_option('site_icon')) { ?>
					<img src="<?php echo wp_get_attachment_image_url(get_option('site_icon'),'thumbnail') ?>" alt="" class="site_icon alignleft">
				<?php } ?>	
					<?php bloginfo( 'name' ); ?> is <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'rebalance' ) ); ?>"><?php printf( esc_html__( 'powered by %s', 'rebalance' ), 'WordPress' ); ?></a>
					<br /><br />
					<?php printf( esc_html__( 'Constellection Theme by %1$s based on %2$s.', 'rebalance' ), '@cogdog', '<a href="http://wordpress.com/themes/rebalance/" rel="designer">Rebalance</a>' ); ?>
				</div><!-- .site-info -->
			</footer><!-- #colophon -->

		</div><!-- .col-width -->
	</div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>