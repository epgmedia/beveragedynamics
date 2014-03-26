<?php

$opt = engine_layout_options();
$total = $wp_query->post_count;
$count = 0;

$author_avatar = get_avatar( get_the_author_meta('ID') );

get_header();

?>

    <div class="row">

        <div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <?php

            if( !is_paged() && $opt['archive_first'] != $opt['archive_layout']) {
                include( locate_template("parts/archive-author.php") );
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

    </div>
    <!-- /.row -->

<?php get_footer(); ?>