<div class="author-meta">
    <?php if ( getAvatarHostName($author_avatar) !== '0.gravatar.com' && getAvatarHostName($author_avatar) !== '1.gravatar.com' ) { // author has image? ?>
        <div class="author-image left"><?php echo get_avatar( get_the_author_meta('ID'), 150, '', 'Author: ' . get_the_author() ) ?></div>
    <?php } ?>
    <h1 class="page-title">Author <?php wp_title(); ?></h1>
    <?php
    if ( get_the_author_meta( 'description' ) !== NULL ){ ?>
        <p><?php the_author_meta( 'description' ); ?></p>
    <?php } ?>
    <?php if ( get_the_author_meta( 'user_email' ) ) { ?>
        <p>Contact the Author: <a href="mailto:<?php the_author_meta( 'user_email' ); ?>">Email</a></p>
    <?php } ?>
</div>

<?php
if( have_posts() ) : ?>
<h2 class="page-title">Recent Stories</h2>
<ul class="small-block-grid-1 large-block-grid-2 grid-<?php echo $opt['archive_first']; ?>">

    <?php while (have_posts()) : the_post(); ?>

        <?php $count++; ?>

        <?php if( $count < 3 ) : ?>
            <li <?php post_class(); ?>>

                <article class="the-post">

                    <div class="featured-image">

                        <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive-first'); ?></a>

                    </div>
                    <!-- /.featured-image -->

                    <?php get_template_part('loop'); ?>

                </article>
                <!-- /.the-post -->

            </li>
        <?php endif; ?>

        <?php if($count == 2) : ?>
            </ul>
            <!-- /.small-block-grid-1 large-block-grid-2 -->
        <?php endif; ?>

        <?php if( $count > 2) : ?>

            <?php if( $count == 3 ): ?>
                <h3 class="page-title"><?php _e('Other stories','engine') ?></h3>
                <ul class="small-block-grid-1 large-block-grid-1 grid-<?php echo $opt['archive_layout']; ?>">
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