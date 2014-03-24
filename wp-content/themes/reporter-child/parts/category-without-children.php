<?php

$opt = engine_layout_options();
$total = $wp_query->post_count;
$count = 0;

$ad_position = NULL;
if ( $total / 2 >= 4 ) {
    $ad_position = round(($total / 2), 0, PHP_ROUND_HALF_DOWN);
};


if( have_posts() ) : ?>

<div class="large-6 small-12 engine-block column center-column">

    <ul class="small-block-grid-1 large-block-grid-1 grid-1">

    <?php while (have_posts()) : the_post(); ?>

        <?php $count++; ?>

        <?php if( $count < ( 2 ) ) : ?>
            <li <?php post_class(); ?>>

                <article class="the-post">
                    <div class="featured-image">
                        <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive-first'); ?></a>
                    </div>
                    <!-- /.featured-image -->
                    <!-- .entry-header -->
                    <header class="entry-header">
                        <div class="entry-meta">
                            <span class="entry-comments"><a href="<?php comments_link(); ?>"><i class="icon-comments"></i><?php comments_number(0, 1, '%'); ?></a></span>
                            <span class="entry-date"><i class="icon-calendar"></i><?php the_time( get_option('date_format') ); ?></span>
                        </div>
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    </header>
                    <!-- /.entry-header -->
                    <div class="entry-content">
                        <?php echo engine_excerpt(35); ?>
                    </div>
                </article>
                <!-- /.the-post -->

            </li>
        <?php endif; ?>

<?php if( $count == 1 ) : ?>
    </ul>
</div>

<div class="small-12 large-4 column right right-rail soldPosition">
    <?php get_template_part("ads/box-top"); ?>
    <div class="widget">
        <?php
        $post_html = '<li><span class=wpp-category>{category}</span><a href={url} class=wpp-post-title>{text_title}</a></li>';
        echo do_shortcode('[wpp header="Most Popular - ' . $category->name . '" limit=7 range="monthly" cat="' . $wpp_cat_id . '" post_type=post stats_comments=0 stats_category=1 post_html="' . $post_html . '"]');
        ?>
    </div>
</div>

<div class="large-8 small-12 engine-block column left center-column">
<?php endif; ?>

    <?php if( $count > 1) : ?>

    <?php if( $count == ( 2 ) ) : ?>

        <h3 class="page-title"><?php _e('Earlier Posts','engine') ?></h3>

        <ul class="posts small-block-grid-1 large-block-grid-<?php echo $opt['archive_layout']; ?> grid-<?php echo $opt['archive_layout']; ?>">
    <?php endif; ?>

        <li <?php post_class(); ?>>
            <article class="the-post">
                <div class="featured-image">
                    <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive'); ?></a>
                </div>
                <!-- /.featured-image -->
                <!-- .entry-header -->
                <header class="entry-header">
                    <div class="entry-meta">
                        <span class="entry-comments"><a href="<?php comments_link(); ?>"><i class="icon-comments"></i><?php comments_number(0, 1, '%'); ?></a></span>
                        <span class="entry-date"><i class="icon-calendar"></i><?php the_time( get_option('date_format') ); ?></span>
                    </div>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                </header>
                <!-- /.entry-header -->
                <div class="entry-content">
                    <?php
                        echo engine_excerpt(30);
                    ?>
                </div>

            </article>
            <!-- /.the-post -->
        </li>

    <?php
    /**
     * Leaderboard ad in stream
     */
    if( $count == $ad_position ) {
    ?>
        </ul>
    </div>
    <div class="large-12 small-12 column center-column soldPosition">
        <?php get_template_part("ads/leaderboard-middle"); ?>
    </div>
    <div class="large-8 left small-12 engine-block column center-column">
        <ul class="posts small-block-grid-1 large-block-grid-<?php echo $opt['archive_layout']; ?> grid-<?php echo $opt['archive_layout']; ?>">
    <?php } //Endif leaderboard ?>

    <?php if( $count == $total ) { ?>
        </ul>
    <?php } //Endif $count == $total ?>

<?php endif; //Endif $total > 6 ?>

<?php endwhile; else: ?>
    <span class="label"><?php _e('No posts found.','engine'); ?></span>
<?php endif; ?>
</div>
