<?php
$opt = engine_layout_options();
$category = get_category( get_query_var( 'cat' ) );
$cat_id = $category->cat_ID;
$args = array(
    'parent' => $cat_id,
    'orderby' => 'count',
    'order' => 'desc'
);
$cats = get_categories($args);
?>

<div class="large-3 small-12 engine-block column left-rail">
    <!-- Category list -->
    <?php display_category_list($category); ?>
</div>

<?php
if ( !empty($cats) ):
    ?>
    <div class="large-9 small-12 engine-block column center-column">
        <?php get_template_part("parts/category-slider"); ?>
    </div>
    <?php
    $cat_args = array( "posts" => 2 );
    $categoryArgs = wp_parse_args($cat_args, $args);
    category_page_subcategories($category, $categoryArgs, 0);

else:

    $total = $wp_query->post_count;
    $count = 0;

    if( have_posts() ) : ?>

        <div class="large-9 small-12 engine-block column center-column">

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
                            <span class="read-more"><a href="<?php the_permalink(); ?>">Read More &raquo;</a></span>
                        </div>


                    </article>
                    <!-- /.the-post -->

                </li>
            <?php endif; ?>

            <?php if( $count == 1 ) : ?>
                </ul>
                </div>

                <div class="span12 no-margin small-12 engine-block column block-Clear"></div>

                <div class="large-12 small-12 engine-block column left center-column">
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
                            <?php echo engine_excerpt(30); ?> <span class="read-more"><a href="<?php the_permalink(); ?>">Read More &raquo;</a></span>
                        </div>

                    </article>
                    <!-- /.the-post -->
                </li>

                <?php if( $count == $total ) { ?>
                    </ul>
                <?php } //Endif $count == $total ?>

            <?php endif; //Endif $total > 6 ?>

        <?php endwhile; else: ?>
        <span class="label"><?php _e('No posts found.','engine'); ?></span>
    <?php endif; ?>
    </div> <?php
    get_template_part('parts/pagination');

endif; ?>