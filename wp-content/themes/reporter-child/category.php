<?php get_header(); ?>

	<div class="row">

		<div id="content" class="content small-12 column <?php echo engine_content_position(); ?>">

            <div class="entry-content">

                <h1 class="page-title"><?php _e('Latest from','engine'); ?> <?php wp_title(); ?></h1>

                <?php
                    $opt = engine_layout_options();
                    // saving to reuse in different functions
                    $category = get_category( get_query_var( 'cat' ) );
                ?>
                <div class="content small-12 column large-12 left">

                    <div class="large-2 small-12 engine-block column left-rail">
                        <!-- Category list -->
                        <?php display_category_list($category); ?>
                    </div>
                    <?php

                    // top-level category?
                    $cat_id = $category->cat_ID;

                    $args = array(
                        'parent' => $cat_id,
                        'orderby' => 'ID',
                    );
                    $cats = get_categories($args);

                    // landing page?

                    if ( !empty($cats) ) {

                        get_template_part("parts/category-with-children");

                    } else {

                        get_template_part("parts/category-without-children");

                        get_template_part('parts/pagination');

                    } ?>

                </div>

            </div>

		</div>

	</div>
	<!-- /.row -->

<?php get_footer(); ?>