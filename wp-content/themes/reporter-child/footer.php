	    <?php the_ad_position('Leaderboard', 'Bottom'); ?>

    </div>
	<!-- /.main.row -->

	<?php if( is_active_sidebar('footer_top') ) : ?>
	<div class="footer-fullwidth-widgets container">

		<ul class="small-block-grid-1 large-block-grid-4">
			<?php dynamic_sidebar('footer_top'); ?>
		</ul>

        <?php get_template_part('parts/adchoices'); ?>

	</div>
	<!-- /.footer-fullwidth-widgets container -->
	<?php endif; ?>

	<?php if( is_active_sidebar('footer_bottom') ) : ?>
	<div class="footer-widgets container">

		<ul class="small-block-grid-1 large-block-grid-4">
			<?php dynamic_sidebar('footer_bottom'); ?>
		</ul>

		<p class="footer-info"><?php bloginfo('name'); ?> &copy; <?php echo date('Y'); ?> | Theme by <a href="http://www.epgmediallc.com/" target="_blank">EPG Media</a> and  <a href="http://www.designerthemes.com/" target="_parent">DesignerThemes.com</a></p>

	</div>
	<!-- /.footer-widgets container -->
	<?php endif; ?>

<?php wp_footer(); ?>
<?php get_template_part('parts/quantcast'); ?>

    </body>
</html>