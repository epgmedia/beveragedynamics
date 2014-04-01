<?php

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

    <?php endwhile; ?>
    </div>
<?php else: ?>
    <span class="label"><?php _e('No posts found.','engine'); ?></span>
<?php endif; ?>