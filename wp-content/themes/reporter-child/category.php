<?php

get_header(); ?>

	<div class="row">

        <?php the_ad_position('Leaderboard', 'Top'); ?>

		<div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <div class="entry-content">

                <h1 class="page-title"><?php _e('Latest from','engine'); ?> <?php wp_title(); ?></h1>

                <div class="content small-12 column large-12 left">

                    <?php get_template_part("parts/category-content");?>

                </div>

            </div>

		</div>

        <div class="sidebar small-12 large-4 column" id="sidebar">

            <?php get_sidebar(); ?>

        </div>

        <?php the_ad_position('Leaderboard', 'Bottom'); ?>

	</div>
	<!-- /.row -->

<?php get_footer();