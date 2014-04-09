<?php
/**
 * CATEGORY FILTER
 *
 * Removes "Top Stories" and "Uncategorized" categories from printed category
 * lists.
 *
 * @param array $thelist
 * @param string $separator
 * @return string
 *
 */
function the_category_filter($thelist, $separator = ' ') {
    if(!defined('WP_ADMIN')) {
        //Category IDs to exclude
        //Excludes Uncategorized, Top Stories
        $exclude = array(1, 91);
        $exclude2 = array();
        foreach($exclude as $c) {
            $exclude2[] = get_cat_name($c);
        }
        $cats = explode($separator,$thelist);
        $newlist = array();
        foreach($cats as $cat) {
            $catname = trim(strip_tags($cat));
            if(!in_array($catname,$exclude2))
                $newlist[] = $cat;
        }
        return implode($separator,$newlist);
    } else {
        return $thelist;
    }
}
add_filter('the_category','the_category_filter', 10, 2);

/**
 *
 * Get the content position with the sidebar options
 *
 * Changed content sizing for sidebar
 *
 */
function engine_content_position() {

    if( !is_admin() ) {

        global $reporter_data;

        // Default
        $sidebar_position = 'right';
        $content_position = 'large-8 left';

        // Default sidebar positions
        if( is_single() )
            if( isset($reporter_data['post_sidebar_pos']) )
                $sidebar_position = $reporter_data['post_sidebar_pos'];

        if( is_page() )
            if( isset($reporter_data['page_sidebar_pos']) )
                $sidebar_position = $reporter_data['page_sidebar_pos'];

        if( is_archive() )
            if( isset($reporter_data['archive_sidebar_pos']) )
                $sidebar_position = $reporter_data['archive_sidebar_pos'];

        // Override if sidebar position is set for post/page metabox
        if( is_singular() ) {

            $single_sidebar_position = get_post_meta(
                get_the_ID(),
                'engine_sidebar_pos',
                TRUE
            );

            if( $single_sidebar_position != '' )
                $sidebar_position = $single_sidebar_position;

        }

        if( $sidebar_position == 'right-sidebar' ) $content_position = 'large-8 left';
        if( $sidebar_position == 'left-sidebar' ) $content_position = 'large-8 right';
        if( $sidebar_position == 'no-sidebar' ) $content_position = 'large-12';

        $output = $content_position;

        return $output;

    }
}

/**
 * Breadcrumb Nav
 *
 * Simple website breadcrumb. Placed on Post pages but compatible with others.
 *
 * Link: http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
 */
function dimox_breadcrumbs() {

    /* === OPTIONS === */
    $text['home']   = 'Home'; // text for the 'Home' link
    $text['category'] = 'Archive by Category "%s"'; // text for a category page
    $text['search']  = 'Search Results for "%s" Query'; // text for a search results page
    $text['tag']   = 'Posts Tagged "%s"'; // text for a tag page
    $text['author']  = 'Articles Posted by %s'; // text for an author page
    $text['404']   = 'Error 404'; // text for the 404 page

    $show_current  = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
    $show_on_home  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_title   = 1; // 1 - show the title for the links, 0 - don't show
    $delimiter   = ''; // delimiter between crumbs
    $before     = '<span class="current">'; // tag before the current crumb
    $after     = '</span>'; // tag after the current crumb
    /* === END OF OPTIONS === */

    global $post;
    $home_link  = home_url('/');
    $link_before = '<li>';
    $link_after  = '</li>';
    $link_attr  = '';
    $link     = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id  = $parent_id_2 = $post->post_parent;
    $frontpage_id = get_option('page_on_front');

    if (is_home() || is_front_page()) {

        if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

    } else {

        echo '<ul class="breadcrumbs">';
        if ($show_home_link == 1) {
            echo '<a href="' . $home_link . '">' . $text['home'] . '</a>';
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
        }

        if ( is_category() ) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;

        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current == 1) echo $before . get_the_title() . $after;

        } elseif ( is_page() && $parent_id ) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;

        } elseif ( has_post_format() && !is_singular() ) {
            echo get_post_format_string( get_post_format() );
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

        echo '</ul><!-- .breadcrumbs -->';

    }
} // end dimox_breadcrumbs()

/**
 *
 * Local Avatar only
 *
 * Removes default avatar from author pages. Used on author pages.
 *
 * @var string $htmlfragment = HTML object, string of HTML
 *
 * @return string|NULL Returns string or null if nothing available
 *
 */
function getAvatarHostName( $htmlFragment ) {
    $string = <<<XML
$htmlFragment
XML;
    $xml = simplexml_load_string( $string );
    $imageSrc = parse_url($xml['src']);

    if ( $imageSrc['host'] ) {
        return $imageSrc['host'];
    }
    return NULL;
}

/**
 * Class Walker_Category_Find_Parents -
 * Extends the Walker_Category
 *
 * Adds "has-children" class to parent lists that have sub lists. Used in category
 * list widget
 */
class Walker_Category_Find_Parents extends Walker_Category {

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

        extract($args);

        $cat_name = esc_attr( $category->name );
        $cat_name = apply_filters( 'list_cats', $cat_name, $category );
        // begin link
        $link = '<a href="' . esc_url( get_term_link($category) ) . '" ';

        // sets title
        if ( $use_desc_for_title == 0 || empty($category->description) )
            $link .= 'title="' . esc_attr(
                    sprintf( __( 'View all posts filed under %s' ), $cat_name)
                ) . '"';
        else
            $link .= 'title="' . esc_attr(
                    strip_tags(
                        apply_filters(
                            'category_description',
                            $category->description,
                            $category
                        )
                    )
                ) . '"';
        $link .= '>';
        $link .= $cat_name . '</a>';

        if ( !empty($show_count) )
            $link .= ' (' . intval($category->count) . ')';

        if ( 'list' == $args['style'] ) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;

            $termchildren = get_term_children( $category->term_id, $category->taxonomy );
            if(count($termchildren) > 0){
                $class .=  ' has-children';
            }

            if ( !empty($current_category) ) {
                $_current_category = get_term( $current_category, $category->taxonomy );
                if ( $category->term_id == $current_category )
                    $class .=  ' current-cat';
                elseif ( $category->term_id == $_current_category->parent )
                    $class .=  ' current-cat-parent';
            }
            $output .=  ' class="' . $class . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}

/**
 * Display categories
 *
 * Displays child categories in list display. If no children, returns siblings.
 *
 * @var object $post_cat get_categories() object
 */
function display_category_list($post_cat) {

    $category = $post_cat;

    $before_widget = '<div id="category-page-List" class="widget displayCategoriesWidget">';
    $after_widget = '</div>';
    $before_title = '<h3 class="widget-title">';
    $after_title = '</h3>';

    $cat_id = $category->cat_ID;
    $parent_id = $category->parent;


    $category_args = array( 'child_of' => $cat_id );
    $categories = get_categories($category_args);
    if ( empty($categories) ) {
        $cat_id = $category->parent;
    }


    $title = '&raquo; ' . get_cat_name($cat_id);

    echo $before_widget;

    if ($parent_id == 0) {
        echo $before_title . $title . $after_title;
    }

    $args = array(
        'orderby'            => 'ID',
        'order'              => 'ASC',
        'style'              => 'list',
        'hide_empty'         => 0,
        'child_of'           => $cat_id,
        'exclude'            => '1,91',
        'hierarchical'       => 1,
        'title_li'           => '',
        'show_option_none'   => '',
        'echo'               => 1,
        'taxonomy'           => 'category',
        'walker'             => new Walker_Category_Find_Parents()
    );
    echo '<ul class="subcategories">';
    if ($parent_id !== 0) {

        echo '<li class="cat-item cat-item-' . $parent_id . ' current-parent-cat">' .
            ' <a href="' . get_category_link($parent_id) .'">&laquo; ' .
            get_cat_name($parent_id) .'</a>' .
            '</li>';

    }
    wp_list_categories($args);
    echo "</ul>";
    echo $after_widget;
}

/**
 * Slider for Category Pages
 *
 * Displays Top or Recent stories on category pages.
 *
 * @param array $arguments Array of query arguments
 */
function display_category_slider($arguments) {

    extract($arguments);

    $args = array(
        'posts_per_page' => $qty,
        'cat' => $cat_id,
    );
    $q = new WP_Query($args);
    if( $q->have_posts() ) : ?>
        <div class="slider flexslider" data-autoplay="<?php echo $autoplay; ?>" data-random="<?php echo $random; ?>">
            <ul class="slides">
                <?php while ( $q->have_posts() ) : $q->the_post();
                    $i = 0;
                    if ( in_category( 91, $post->ID ) ) {
                        $i = 3;
                        ?>
                        <li <?php post_class(); ?>>
                            <article class="the-post">
                                <div class="featured-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php engine_thumbnail($th_size); ?>
                                    </a>
                                </div>
                                <!-- /.featured-image -->
                                <!-- .entry-header -->
                                <header class="entry-header">
                                    <div class="entry-meta">
                                        <span class="entry-comments">
                                            <a href="<?php comments_link(); ?>">
                                                <i class="icon-comments"></i>
                                                <?php comments_number(0, 1, '%'); ?>
                                            </a>
                                        </span>
                                        <span class="entry-category">
                                            <i class="icon-folder-open"></i>
                                            <?php the_category(', '); ?>
                                        </span>
                                        <span class="entry-date">
                                            <i class="icon-calendar"></i>
                                            <?php the_time( get_option('date_format') ); ?>
                                        </span>
                                    </div>
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                </header>
                                <!-- /.entry-header -->
                                <?php if( $excerpt_length != '0' ): ?>
                                    <div class="entry-content">
                                        <?php echo wpautop(engine_excerpt($excerpt_length)); ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                            <!-- /.the-post -->
                        </li>
                    <?php
                    }
                    while ( $i < 3 ) { ?>
                        <li <?php post_class(); ?>>
                            <article class="the-post">
                                <div class="featured-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php engine_thumbnail($th_size); ?>
                                    </a>
                                </div>
                                <!-- /.featured-image -->
                                <!-- .entry-header -->
                                <header class="entry-header">
                                    <div class="entry-meta">
                                        <span class="entry-comments">
                                            <a href="<?php comments_link(); ?>">
                                                <i class="icon-comments"></i>
                                                <?php comments_number(0, 1, '%'); ?>
                                            </a>
                                        </span>
                                        <span class="entry-category">
                                            <i class="icon-folder-open"></i>
                                            <?php the_category(', '); ?>
                                        </span>
                                        <span class="entry-date">
                                            <i class="icon-calendar"></i>
                                            <?php the_time( get_option('date_format') ); ?>
                                        </span>
                                    </div>
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                </header>
                                <!-- /.entry-header -->
                                <?php if( $excerpt_length != '0' ): ?>
                                    <div class="entry-content">
                                        <?php echo wpautop(engine_excerpt($excerpt_length)); ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                            <!-- /.the-post -->
                        </li>
                        <?php $i++;
                    }
                endwhile; ?>
            </ul>
        </div>
    <?php
    endif;
    wp_reset_query();
}

/**
 * Subcategory Posts
 *
 * Posts for each subcategory on a category page.
 *
 * @param object $category get_categories object
 * @param array $args Array of WP_Query arguments
 * @param int $clear_div Clears sections on 1,
 */
function category_page_subcategories($category, $args, $clear_div = 1) {

    extract($args);

    $content_start = '<div class="large-12 left small-12 engine-block column center-column">';
    $title_start = '<h3 class="widget-title">';
    $cat_desc = NULL;
    if ( $category->description ) {
        $cat_desc = '<p class="description">' . $category->description . '</p>';
    }
    $title_end = '</h3>';
    $clear_both = '';
    $category_post = '';
    if ( $clear_div === 1 ) {
        $category_post = 'category-post ';
        $clear_both = '<div class="span12 no-margin small-12 engine-block column block-Clear"></div>';
    }
    $content_end = '</div>';

    // title and description
    echo $content_start;
    echo $title_start . $category->name . $title_end;
    echo $cat_desc;

    // the good stuff
    $news_cat_ID = $category->cat_ID;
    $news_args = array(
        'parent' => $news_cat_ID,
        'orderby' => $orderby,
        'order' => $order
    );
    $news_cats   = get_categories($news_args);
    $news_query  = new WP_Query();

    $count = 0;
    foreach ($news_cats as $news_cat):
        $count++;
        echo $clear_both;
        echo '<div class="' . $category_post . 'large-12 small-12 column left engine-block center-column">';
        echo '<h4 class="widget-title">' . $news_cat->name .
            '<span><a href="' . get_category_link($news_cat->cat_ID) . '"> more&raquo;</a></span>' . '</h4>';

        echo '<ul class="posts title_meta_thumb_2 small-block-grid-2 large-block-grid-2">';
        // query for each category
        $news_query->query('posts_per_page=' . $args['posts'] . '&cat=' . $news_cat->term_id);

        if ( $news_query->have_posts() ):
            while ( $news_query->have_posts() ): $news_query->the_post(); ?>
                <li class="title-meta-thumb">
                    <article class="the-post">
                        <div class="featured-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php engine_thumbnail('archive-first'); ?>
                            </a>
                        </div>
                        <header class="entry-header">
                            <div class="entry-meta">
                                <span class="entry-comments">
                                    <a href="<?php comments_link(); ?>">
                                        <i class="icon-comments"></i><?php comments_number(0, 1, '%'); ?>
                                    </a>
                                </span>
                                <span class="entry-date">
                                    <i class="icon-calendar"></i>
                                    <?php the_time( get_option('date_format') ); ?>
                                </span>
                            </div>
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                        <!-- /.entry-header -->
                        <div class="entry-content">
                            <?php
                            echo engine_excerpt(25);
                            ?>
                        </div>
                    </article>
                </li>
            <?php
            endwhile;

        endif;

        echo '</ul>';

        echo '</div>';

    endforeach;

    echo $content_end;
}

/**
 * Post Authors
 *
 * 1. Checks for certain author names and returns nothing for
 * Beverage Dynamics and Digital
 *
 * 2. Checks for Custom Author field and displays author
 * 3. If no custom author, displays Wordpress default.
 * 4. Adds "By " if missing.
 *
 * @return string|bool|null $author Echos the author or returns nothing.
 *      True on success, false on nothing
 */

function epg_author($return = TRUE) {
    global $post;

    // quick author check to filter "Digital" and "Beverage Dynamics"
    $authorId = get_the_author_meta( 'ID' );
    if ( get_field('author_name') == NULL && ($authorId === 1 || $authorId === 16) ) {
        return NULL;
    }

    $author = ( get_field('author_name') != NULL ? get_field('author_name') : get_the_author() );

    if ( $author == get_the_author() ) {
        $authorPostsUrl = get_author_posts_url( $authorId );
        $author = '<a href="' . $authorPostsUrl . '">' . $author . '</a>';
        $author = 'By ' . $author;
    } elseif ( $author == get_field('author_name') ) {
        if ( mb_substr($author, 0, 3) !== 'By ' ) {
            $author = 'By ' . $author;
        }
    }
    // if we have something to work with, display it on page. Otherwise, panic
    if ( $author !== NULL ){

        return $author;

    }

    return NULL;

}
/** Returns the Author for Var/Checks */
function get_epg_the_author() {

    if ( epg_author() !== NULL ){

        return epg_author();
    }

    return NULL;

}

/** Prints the Author if available */
function epg_the_author() {

    if ( epg_author() !== NULL ){

        echo epg_author();

        return TRUE;
    }

    return NULL;
}

/**
 * Adds photographer credit
 */
function get_epg_the_photographer() {

    if ( get_field('photographer') != NULL ) {
        return TRUE;
    }

    return FALSE;
}

/**
 * Adds photographer credit
 */
function epg_the_photographer() {
    if ( get_field('photographer') != NULL ) {
        echo 'Photos by ' . get_field('photographer');

        return TRUE;
    }

    return FALSE;
}
