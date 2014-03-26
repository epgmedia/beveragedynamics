<?php
/**
 * Template Name: Enewsletter Subscribe
 */

get_header(); ?>

	<div class="row">

		<div class="content small-12 column <?php echo engine_content_position(); ?>">

			<div <?php post_class(); ?>>

				<div class="entry-content">
					<h1 class="page-title"><?php the_title(); ?></h1>

                    <?php the_content(); ?>

                    <div class="subscribe-form">
                        <?php get_template_part("parts/enews-subscribe"); ?>
                    </div>
				</div>

			</div>
			<!-- /.post -->

		</div>
		<!-- /.content small-12 large-9 column -->

		<?php if( engine_content_position() != 'large-12' ) : ?>
		<div class="sidebar small-12 large-4 column" id="sidebar">
			<?php get_sidebar(); ?>
		</div>
		<!-- /#sidebar.sidebar small-12 large-4 column -->
		<?php endif; ?>

	</div>
	<!-- /.row -->

<?php get_footer(); ?>