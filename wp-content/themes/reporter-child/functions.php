<?php
define('CHILDDIR', get_stylesheet_directory());
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
 * Register AQ Page Builder Blocks
 *
 * Add Theme parts after theme is created
 * Makes Page builder Child-themeable.
 */
add_action( 'after_setup_theme', function() {

    // add blocks
    require_once( CHILDDIR . '/blocks/epg-stock-index-block.php');
    require_once( CHILDDIR . '/blocks/epg-ad-position-block.php');
    require_once( CHILDDIR . '/blocks/epg-ad-position-test-block.php');
    require_once( CHILDDIR . '/blocks/aq-widgets-block.php');

    // register blocks
    aq_register_block('EPG_Stock_Index_Block');
    aq_register_block('EPG_Ad_Position_Block');
    aq_register_block('EPG_Ad_Position_Test_Block');
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
        'category_sidebar' => __('Category Sidebar', 'engine')
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
    register_widget('DisplayCategoriesWidget');
    register_widget('epg_related_stories_widget');/*
    require_once( CHILDDIR . '/blocks/ai1ec-agenda-block.php');
    aq_register_block('Ai1ec_Agenda_Block');*/
}
/**
 * Register New Wordpress Widgets
 */
add_action('widgets_init', 'epg_child_theme_widget_init');

/**
 * Breadcrumb Nav
 * Simple website breadcrumb. Placed on Post pages.
 * Link: http://cazue.com/articles/wordpress-creating-breadcrumbs-without-a-plugin-2013
 */
function the_breadcrumb() {
    global $post;
    echo '<ul id="breadcrumbs">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li><li class="separator"> &#187; </li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li class="separator"> &#187; </li><li> ');
            if (is_single()) {
                echo '</li><li class="separator"> &#187; </li><li>';
                the_title();
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


function iterate_terms($post_id = '', $search_options = array(), $AND = NULL ) {
    global $post, $wpdb;

    if ( $AND !== NULL ) {
        $post_args['tax_query'][0]['operator'] = $AND;
    }
    $output = array();
    $defaults = array(
        'post_type' => array('post')
    );
    $qargs = array(
        'fields' => 'ids',
        'orderby' => 'count',
        'order' => 'ASC'
    );
    $options = wp_parse_args( $search_options, $defaults );
    $terms_set = wp_get_post_terms( $post_id, $options['taxonomy'], $qargs );
    //Make sure each returned term id to be an integer.
    $terms_set = array_map('intval', $terms_set);

    //Store a copy that we'll be reducing by one item for each iteration.
    $terms_to_iterate = $terms_set;

    $post_args = array(
        'fields' => 'ids',
        'post_type' => $options['post_type'],
        'post__not_in' => array($post_id, 1, 91),
        'posts_per_page' => 50
    );

    while( count( $terms_to_iterate ) >= 1 ) {

        $post_args['tax_query'] = array(
            array(
                'taxonomy' => $options['taxonomy'],
                'field' => 'id',
                'terms' => $terms_to_iterate
            )
        );
        $posts = get_posts( $post_args );
        foreach( $posts as $id ) {
            $id = intval( $id );
            if( !in_array( $id, $output) ) {
                $output[] = $id;
            }
        }
        array_pop( $terms_to_iterate );
    }

    return $output;
}

/**
 * Checks for local avatar or returns nothing
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