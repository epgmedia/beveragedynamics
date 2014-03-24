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

/**
 * Popular Posts ids
 */
$wpp_args = array( 'child_of' => $cat_id );
$wpp_cats = get_categories($wpp_args);
$wpp_cat_id = array($cat_id);
foreach ($wpp_cats as $cat) {
    $wpp_cat_id[] = $cat->cat_ID;
}
$wpp_cat_id = implode(', ', $wpp_cat_id);

get_header(); ?>

	<div class="row">

		<div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <div class="entry-content">

                <h1 class="page-title"><?php _e('Latest from','engine'); ?> <?php wp_title(); ?></h1>

                <div class="small-12 large-12 column center-column soldPosition">
                    <?php get_template_part("ads/leaderboard-top"); ?>
                </div>

                <div class="content small-12 column large-12 left">

                    <div class="large-2 small-12 engine-block column left-rail">
                        <!-- Category list -->
                        <?php display_category_list($category); ?>
                    </div>

                    <?php if ( !empty($cats) ): ?>

                        <?php get_template_part("parts/category-slider"); ?>

                        <div class="small-12 large-4 column right right-rail soldPosition">
                            <?php get_template_part("ads/box-top"); ?>
                            <div class="widget">
                                <?php
                                    $post_html = '<li><span class=wpp-category>{category}</span><a href={url} class=wpp-post-title>{text_title}</a></li>';
                                    echo do_shortcode('[wpp header="Most Popular - ' . $category->name . '" limit=7 range="monthly" cat="' . $wpp_cat_id . '" post_type=post stats_comments=0 stats_category=1 post_html="' . $post_html . '"]');
                                ?>
                            </div>
                        </div>

                        <?php
                        $cat_args = array( "posts" => 2 );
                        $categoryArgs = wp_parse_args($cat_args, $args);
                        category_page_subcategories($category, $categoryArgs, 0);

                    else:

                        get_template_part("parts/category-without-children");

                        get_template_part('parts/pagination');

                    endif; ?>

                </div>

                <div class="small-12 large-12 column center-column soldPosition">
                    <?php get_template_part("ads/leaderboard-bottom"); ?>
                </div>

            </div>

		</div>

	</div>
	<!-- /.row -->

<?php get_footer();