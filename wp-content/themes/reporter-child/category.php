<?php
$opt = engine_layout_options();


/** Category Information */
$category = get_category( get_query_var( 'cat' ) );
$cat_id = $category->cat_ID;
$args = array(
    'parent' => $cat_id,
    'orderby' => 'count',
    'order' => 'desc'
);
$cat_args = array( "posts" => 2 );
$categoryArgs = wp_parse_args($cat_args, $args);

$cats = get_categories($args);

/** Loop Information */
$total = $wp_query->post_count;
$count = 0;

/** Begin Content */

get_header();

?>

	<div class="row">

		<div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <div class="entry-content">

                <h1 class="page-title"><?php _e('Latest from','engine'); ?> <?php wp_title(); ?></h1>

                <div class="content small-12 column large-12 left">

                    <div class="large-3 small-12 engine-block column left-rail">
                        <!-- Subcategory Menu -->
                        <?php display_category_list($category); ?>
                    </div>

                    <?php

                    if ( !empty($cats) ):

                        include( locate_template( "parts/category-slider.php" ) );

                        category_page_subcategories($category, $categoryArgs, 0);

                    else:

                        include( locate_template( "parts/category-content.php") );

                        include( locate_template( "parts/pagination.php" ) );

                    endif; ?>

                </div>

            </div>

		</div>

        <div class="sidebar small-12 large-4 column" id="sidebar">

            <?php get_sidebar(); ?>

        </div>



	</div>
	<!-- /.row -->

<?php

get_footer();