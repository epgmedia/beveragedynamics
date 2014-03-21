<?php

$category = get_category( get_query_var( 'cat' ) );

$news_cat_ID = $category->cat_ID;
$news_args = array(
    'parent' => $news_cat_ID,
    'orderby' => 'ID',
);
$news_cats   = get_categories($news_args);
$news_query  = new WP_Query();

foreach ($news_cats as $news_cat): ?>
    <div class="span12 no-margin small-12 engine-block column block-Clear"></div>

    <div class="category-post large-8 small-12 column left engine-block center-column">

        <h4 class="widget-title">
            <?php echo esc_html($news_cat->name); ?>
            <span><a href="<?php echo get_category_link($news_cat->cat_ID); ?>"> more&raquo;</a></span>
        </h4>

        <ul class="posts title_meta_thumb_2 small-block-grid-2 large-block-grid-2">

        <?php
        $news_query->query('posts_per_page=2&cat=' . $news_cat->term_id); ?>
        <?php if ( $news_query->have_posts() ):
            while ( $news_query->have_posts() ): $news_query->the_post(); ?>
                <li class="title-meta-thumb">
                    <article class="the-post">

                        <div class="featured-image">

                            <a href="<?php the_permalink(); ?>"><?php engine_thumbnail('archive-first'); ?></a>

                        </div>

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
                                echo engine_excerpt(25);
                            ?>

                        </div>

                    </article>
                </li>
            <?php endwhile;

        endif; ?>
        </ul>
    </div>
<?php endforeach;