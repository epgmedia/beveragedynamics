<?php get_header(); ?>

    <div class="row">

        <?php the_ad_position('Leaderboard', 'Top'); ?>

        <div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <?php

            $opt = engine_layout_options();

            if( !is_paged() && $opt['archive_first'] != $opt['archive_layout']) {
                get_template_part('parts/archive-author');
            } else {
                get_template_part('parts/archive-list');
            }

            ?>

            <?php get_template_part('parts/pagination'); ?>

        </div>
        <!-- /.content small-12 large-8 column -->

        <?php if( engine_content_position() != 'large-12' ) : ?>
            <div class="sidebar small-12 large-4 column" id="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>

        <?php the_ad_position('Leaderboard', 'Bottom'); ?>

    </div>
    <!-- /.row -->

<?php get_footer(); ?>