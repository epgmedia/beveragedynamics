<?php
define('CHILDDIR', get_stylesheet_directory());
/*
 *
 * Get the content position with the sidebar options
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
 *
 * Removes "Top Stories" and "Uncategorized" categories from printed category lists.
 *
 * @param $thelist
 * @param string $separator
 * @return string
 */
function the_category_filter( $thelist, $separator=' ' ) {
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
function epg_google_ad_position_widget_init() {
    register_widget('epg_google_ad_position_widget');
}
add_action('widgets_init', 'epg_google_ad_position_widget_init');

/*
Plugin Name: Display Categories Widget
Description: Display Categories Widget to display on your sidebar, this will get the title and category id
Plugin URI: http://www.iteamweb.com/
Version: 1.0
Author: Suresh Baskaran
License: GPL
*/
require_once( CHILDDIR . '/widgets/epg-display-categories-widget.php');
function DisplayCategoriesWidget_init() {
    register_widget('DisplayCategoriesWidget');
}
add_action('widgets_init', 'DisplayCategoriesWidget_init');


/**
 * Top menu
 */
function register_top_menu() {
    register_nav_menu('secondary-menu',__( 'Secondary Menu' ));
}
add_action( 'init', 'register_top_menu' );


/**
 * Additional widgets to be placed with Aqua Page Builder
 * Will not display on site directly
 */
function register_on_page_widgets () {

    $sidebars = array(
        'category_sidebar' => __('Category Sidebar', 'engine')

    );

    foreach ($sidebars as $key => $value) {

        register_sidebar(array('name'=> $value,
            'id' => $key,
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        ));

    }
}
add_action('init', 'register_on_page_widgets');