<?php

/**
 * MENU AND SIDEBAR REGISTRATION
 */

add_action('init', function() {
    /**
     * Top menu - Small menu for links.
     */
    register_nav_menu('secondary-menu', __('Secondary Menu'));

    /**
     * Additional widgets to be placed with Aqua Page Builder
     * Will not display on site directly, only through AQPB
     */
    $sidebars = array(
        'optional_sidebar_1' => __('Home Page Widget', 'engine'),
        'optional_sidebar_2' => __('Optional Sidebar 1', 'engine')
    );

    foreach ($sidebars as $key => $value) {
        register_sidebar(array(
                'name' => $value,
                'id' => $key,
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>'
            )
        );
    }
});

/**
 * WIDGET REGISTRATION
 */
add_action('widgets_init', function() {

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

    /*
    Plugin Name: Most Popular Stories
    Description: Display Most Popular Stories
    License: GPL
    */
    require_once( CHILDDIR . '/widgets/epg-most-popular-widget.php');

    /**
     * EPG Social Media Widget
     * A new widget for social media profiles. Yeah. I know. But this
     * one is slightly different and not as annoying.
     */
    require_once( CHILDDIR . '/widgets/epg-social-media-widget.php');

    /**
     * Register New Wordpress Widgets
     */
    register_widget('epg_google_ad_position_widget');
    register_widget('displayCategoriesWidget');
    register_widget('epg_related_stories_widget');
    register_widget('epg_most_popular_stories');
    register_widget('epg_social_widget');

});
