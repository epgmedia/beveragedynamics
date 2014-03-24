<?php
define('CHILDDIR', get_stylesheet_directory());
define('CHILDURI', get_stylesheet_directory_uri());
define('CHILDVERSION', 'v0.0.1');
/**
 * Additional Styles and Scripts
 *
 */
/**
 * CSS Auto versioning
 *
 * Given a file, i.e. /css/base.css, replaces it with a string containing the
 * file's mtime, i.e. /css/base.1221534296.css.
 *
 * @param $file  The file to be loaded.  Must be an absolute path (i.e.
 *               starting with slash).
 */

function auto_version_css($file) {
    /*
     * Checks what the last modified time was
     * Checks:
     * 1. Equal modified time       AND
     * 2. Stylesheet option is set  AND
     * 3. A time has been set to query AND
     * 4. The new stylesheet exists
     *
     * If it's all true, returns the modified file.
     */
    clearstatcache();
    $update_option = 'child_stylesheet_modified_time';
    $lastModifiedTime = get_option($update_option, "Time Not Set");
    $currentStylesheet = get_option($file, 'Stylesheet Not Set');
    $modifiedTime = filemtime(CHILDDIR . $file);
     if ( $lastModifiedTime == $modifiedTime
     && $currentStylesheet !== "Stylesheet Not Set"
     && $lastModifiedTime !== "Time Not Set"
     && file_exists(CHILDDIR . $currentStylesheet) ) {
         return $currentStylesheet;
     }
    $stylesheet = $file;
    /*
     * Running the function
     * If all of those aren't met, it's time to create a new stylesheet
     *
     * First we write the new file name
     * Create a var to hold base directory info
     *
     * Then, we check if everything is writable. If it is, we continue.
     * Otherwise, we return the un-cached file.
     *
     */
    // style.css
    $newStylesheetName = substr(strrchr($stylesheet, "/"), 1);
    // style.timestamp.css
    $newFileName = substr($newStylesheetName, 0, -4) . '.' . $modifiedTime . '.css';
    // "/css/" from "/css/style.css"
    $newStyleSheetDirectory = substr($stylesheet, 0, (strlen($stylesheet)-strlen($newStylesheetName)));
    /*
     * Add new file location.
     * Full directory location of new file.
     * /dir/user/www/etc/etc/wp-content/etc/etc/style.css
     */
    $newStyleSheet = CHILDDIR . $newStyleSheetDirectory . $newFileName;
    /*
     * Add file to folder
     * If it's not writable or files, it'll return the base stylesheet.
     * If we can't write, then chances are it wasn't written before.
     */
    if (is_writable(CHILDDIR . $newStyleSheetDirectory)) {
        // check if the file was created
        if (!$handle = fopen($newStyleSheet, 'w')) {
            return $stylesheet;
        }
        $oldStylesheet = file_get_contents(CHILDDIR . $stylesheet); // data
        // Write data to new stylesheet.
        if (fwrite($handle, $oldStylesheet) === FALSE) {
            return $stylesheet;
        }
        // Success, wrote data to file new stylesheet;
        fclose($handle);
    } else {
        return $stylesheet;
    }
    /*
     * Update Database
     * Everything worked and now it's time to update the database and return the new file
     * and then delete the old file.
     */
    $newFileName = $newStyleSheetDirectory . $newFileName;
    update_option($update_option, $modifiedTime);
    update_option($file, $newFileName);
    // And delete the old stylesheet
    if ($currentStylesheet !== "Stylesheet Not Set" ) {
        unlink(CHILDDIR . $currentStylesheet);
    }

    return $newFileName;
}

/**
 * Register style sheet and new script.
 */
function register_child_theme_styles() {
    $stylesheet = '/assets/beverage-dynamics.css'; //file
    $fileName = auto_version_css($stylesheet);
    wp_register_style( 'BDX-Styles', CHILDURI . $fileName, array('theme-style') );
    wp_enqueue_style( 'BDX-Styles' );

    wp_enqueue_script( 'child-js', CHILDURI . '/assets/beverage-scripts.js', array('jquery', 'foundation'), '', TRUE );
}
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_child_theme_styles' );

/**
 *
 * Register AQ Page Builder Blocks
 *
 * Add Theme parts after theme is created
 * Makes Page builder Child-themeable.
 */
add_action( 'after_setup_theme', function() {

    // add blocks
    require_once( CHILDDIR . '/blocks/epg-stock-index-block.php');
    require_once( CHILDDIR . '/blocks/epg-ad-position-block.php');
    require_once( CHILDDIR . '/blocks/aq-widgets-block.php');

    // register blocks
    aq_register_block('EPG_Stock_Index_Block');
    aq_register_block('EPG_Ad_Position_Block');
    aq_register_block('AQ_Widgets_Block');
}, 2 );

/**
 * Register Sidebars and Menus
 *
 * Items to be registered at init
 * Extra sidebars (widget areas)
 * Extra menus
 */
function register_on_page_widgets () {
    /**
     * Top menu - Small menu for links.
     */
    register_nav_menu('secondary-menu', __('Secondary Menu'));

    /**
     * Additional widgets to be placed with Aqua Page Builder
     * Will not display on site directly, only through AQPB
     */
    $sidebars = array(
        'retail_category_sidebar' => __('Retail Sidebar', 'engine'),
        'spirits_category_sidebar' => __('Spirits Sidebar', 'engine'),
        'wine_category_sidebar' => __('Wine Sidebar', 'engine'),
        'beer_category_sidebar' => __('Beer Sidebar', 'engine')
    );

    foreach ($sidebars as $key => $value) {
        register_sidebar(array(
                'name' => $value,
                'id' => $key,
                'before_widget' => '<li id="%1$s" class="widget %2$s">',
                'after_widget' => '</li>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            )
        );
    }
}
add_action('init', 'register_on_page_widgets');

/*
Widget: Ad Position
Description: Adds a widget that takes an ad position's variables and creates a new ad position
*/
require_once( CHILDDIR . '/widgets/epg-google-ad-position-widget.php');
/*
Plugin Name: Display Categories Widget
Description: Display Categories Widget to display on your sidebar, this will get the title and category id
Plugin URI: http://www.iteamweb.com/
Version: 1.0
Author: Suresh Baskaran
License: GPL
*/
require_once( CHILDDIR . '/widgets/epg-display-categories-widget.php');

/*
Widget Name: Related Stories
Description: Displays stories related to the current page category.
 */
require_once( CHILDDIR . '/widgets/epg-related-stories-widget.php');

// put widgets together
function epg_child_theme_widget_init() {
    register_widget('epg_google_ad_position_widget');
    register_widget('displayCategoriesWidget');
    register_widget('epg_related_stories_widget');
}
/**
 * Register New Wordpress Widgets
 */
add_action('widgets_init', 'epg_child_theme_widget_init');

/*
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

            $single_sidebar_position = get_post_meta(get_the_ID(), 'engine_sidebar_pos', TRUE);

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
 *
 * Removes "Top Stories" and "Uncategorized" categories from printed category lists.
 *
 * @param $thelist
 * @param string $separator
 * @return string
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
 * Breadcrumb Nav
 * Simple website breadcrumb. Placed on Post pages.
 * Link: http://cazue.com/articles/wordpress-creating-breadcrumbs-without-a-plugin-2013
 */
function the_breadcrumb() {
    global $post;
    //<li class="separator"> &#187; </li>
    echo '<ul class="breadcrumbs">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li> ');
            if (is_single()) {
                echo '</li><li>';
                echo the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">&#187;</li>';
                }
                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<strong> ';
                echo the_title();
                echo '</strong>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ul>';
}

/**
 * Local Avatar
 *
 * Removes default avatar from author pages.
 *
 * @var $htmlfragment = HTML object, string of HTML
 *
 * Used on avatar pages.
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
 * Class Walker_Category_Find_Parents
 *
 * Extends the Walker_Category
 *
 * Adds "has-children" class to parent lists that have sub lists.
 *
 * Used in category list widget
 *
 */
class Walker_Category_Find_Parents extends Walker_Category {
    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        extract($args);

        $cat_name = esc_attr( $category->name );
        $cat_name = apply_filters( 'list_cats', $cat_name, $category );
        $link = '<a href="' . esc_url( get_term_link($category) ) . '" ';
        if ( $use_desc_for_title == 0 || empty($category->description) )
            $link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s' ), $cat_name) ) . '"';
        else
            $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
        $link .= '>';
        $link .= $cat_name . '</a>';

        if ( !empty($show_count) )
            $link .= ' (' . intval($category->count) . ')';

        if ( 'list' == $args['style'] ) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;

            $termchildren = get_term_children( $category->term_id, $category->taxonomy );
            if(count($termchildren)>0){
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
 * Displays child categories.
 *
 * If no children, returns siblings.
 *
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

                                    <a href="<?php the_permalink(); ?>"><?php engine_thumbnail($th_size); ?></a>

                                </div>
                                <!-- /.featured-image -->

                                <!-- .entry-header -->
                                <header class="entry-header">

                                    <div class="entry-meta">
                                        <span class="entry-comments"><a href="<?php comments_link(); ?>"><i class="icon-comments"></i><?php comments_number(0, 1, '%'); ?></a></span>
                                        <span class="entry-category"><i class="icon-folder-open"></i><?php the_category(', '); ?></span>
                                        <span class="entry-date"><i class="icon-calendar"></i><?php the_time( get_option('date_format') ); ?></span>
                                    </div>

                                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

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

                                    <a href="<?php the_permalink(); ?>"><?php engine_thumbnail($th_size); ?></a>

                                </div>
                                <!-- /.featured-image -->

                                <!-- .entry-header -->
                                <header class="entry-header">

                                    <div class="entry-meta">
                                        <span class="entry-comments"><a href="<?php comments_link(); ?>"><i class="icon-comments"></i><?php comments_number(0, 1, '%'); ?></a></span>
                                        <span class="entry-category"><i class="icon-folder-open"></i><?php the_category(', '); ?></span>
                                        <span class="entry-date"><i class="icon-calendar"></i><?php the_time( get_option('date_format') ); ?></span>
                                    </div>

                                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

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



function category_page_subcategories($category, $args, $clear_div = 1) {

    extract($args);

    $content_start = '<div class="large-8 left small-12 engine-block column center-column">';
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

    $ad_position = NULL;
    $total = count($news_cats);
    if ( $total / 2 > 2 ) {
        $ad_position = (round(($total / 2), 0, PHP_ROUND_HALF_DOWN))-1;
    };

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
                <?php
                endwhile;

            endif;

            echo '</ul>';

        echo '</div>';
        if ($count == $ad_position) {
            echo $content_end; ?>
            <div class="large-12 small-12 column center-column soldPosition">
                <?php get_template_part("ads/leaderboard-middle"); ?>
            </div>
            <div class="small-12 large-4 column right right-rail soldPosition">
                <?php get_template_part("ads/box-middle"); ?>
            </div>
            <?php echo $content_start;
        }

    endforeach;

    echo $content_end;
}