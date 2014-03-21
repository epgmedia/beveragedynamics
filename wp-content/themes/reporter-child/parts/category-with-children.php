<?php 
$opt = engine_layout_options();
$total = $wp_query->post_count;
$count = 0;

$category_div_width = "large-6"; ?>


<div class="large-6 small-12 engine-block column center-column">

    <div class="block-Slider">
        <?php

        $category = get_category( get_query_var( 'cat' ) );

        $category_div_width = "large-12";

        $args = array(
            'th_size'        => 'medium',
            'cat_id'         => $category->cat_ID,
            'qty'            => 4,
            'random'         => '0',
            'autoplay'       => '6000',
            'excerpt_length' => '30'
        );

        display_category_slider($args);

        ?>
    </div>
</div>

<div class="small-12 large-4 column right-rail" id="sidebar">
    <div class="widget widget_text">
        <div class="textWidget">
            <img src="http://www.placehold.it/300x250" />
        </div>
    </div>
</div>

<div class="span12 no-margin small-12 engine-block column block-Clear"></div>

<div class="<?php echo $category_div_width ?> small-12 engine-block column center-column">
    <h3 class="widget-title"><?php wp_title(); ?></h3>

    <?php if ( $category->description ): ?>
    <p class="description"><?php echo $category->description; ?></p>
    <?php endif;
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
    <?php endforeach; ?>
</div>