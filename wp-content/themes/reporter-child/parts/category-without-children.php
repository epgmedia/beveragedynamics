<?php
$opt = engine_layout_options();
$total = $wp_query->post_count;
$count = 0;

$category_div_width = "large-8"; ?>

<?php if(have_posts()) : ?>
<div class="large-6 small-12 engine-block column center-column">

    <ul class="small-block-grid-1 large-block-grid-1 grid-1">

    <?php while (have_posts()) : the_post(); ?>

        <?php $count++; ?>

        <?php if( $count < ($opt['archive_number'] + 1) ) : ?>
            <li <?php post_class(); ?>>

                <article class="the-post">

                    <div class="featured-image">

                        <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive-first'); ?></a>

                    </div>
                    <!-- /.featured-image -->

                    <?php get_template_part('first-loop'); ?>

                </article>
                <!-- /.the-post -->

            </li>
        <?php endif; ?>

        <?php if($count == $opt['archive_number']) : ?>
    </ul>
</div>

<div class="small-12 large-4 column right-rail" id="sidebar">
    <div class="widget widget_text">
        <div class="textWidget">
            <img src="http://www.placehold.it/300x250" />
        </div>
    </div>
</div>

<div class="span12 no-margin small-12 engine-block column block-Clear"></div>
<div class="<?php echo $category_div_width ?> left small-12 engine-block column center-column">
<?php endif; ?>

    <?php if( $count > $opt['archive_number']) : ?>

    <?php if($count == ($opt['archive_number'] + 1) ) : ?>
        <div class="span12 no-margin small-12 engine-block column block-Clear"></div>
        <h3 class="page-title"><?php _e('Earlier Posts','engine') ?></h3>
        <ul class="posts small-block-grid-1 large-block-grid-<?php echo $opt['archive_layout']; ?> grid-<?php echo $opt['archive_layout']; ?>">

    <?php endif; ?>

        <li <?php post_class(); ?>>

            <article class="the-post">

                <div class="featured-image">

                    <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive'); ?></a>

                </div>
                <!-- /.featured-image -->

                <?php get_template_part('loop'); ?>

            </article>
            <!-- /.the-post -->

        </li>

    <?php if( $count == $total ) : ?>
        </ul>
    <?php endif; //Endif $count == $total ?>

<?php endif; //Endif $total > 6 ?>

<?php endwhile; else: ?>
    <span class="label"><?php _e('No posts found.','engine'); ?></span>
<?php endif; ?>
</div>