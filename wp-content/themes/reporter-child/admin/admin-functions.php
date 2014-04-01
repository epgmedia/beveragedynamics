<?php

/**
 * Remove some unneeded menu bar items
 */
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

function add_admin_bar_link($wp_admin_bar) {

    $class = 'epg-media-link';

    $wp_admin_bar->add_menu( array(
        'id' => 'epg-media-link',
        'title' => __( 'EPG Media, LLC' ),
        'href' => __('http://www.epgmediallc.com'),

    ) );
    $wp_admin_bar->add_menu( array(
        'parent' => 'epg-media-link',
        'id' => 'epg-media-time-off',
        'title' => __( 'Time Off Request' ),
        'href' => __('http://www.epgmediallc.com/time-off-request/'),
    ) );
    $wp_admin_bar->add_menu( array(
        'parent' => 'epg-media-link',
        'id' => 'epg-media-support',
        'title' => __( 'IT Request' ),
        'href' => __('http://www.epgmediallc.com/it-request/'),
    ) );

}
add_action('admin_bar_menu', 'add_admin_bar_link', 50);

/*
Add Theme editor link to admin bar for admins
*/

/** Remove the menu button called 'appearance' (including widgets and menus) because you will add the links manually.
function remove_admin_bar_menu() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('appearance');
} add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_menu' );

**/

/** Now add your own appearance button. I named it 'design'.
function add_admin_bar_menu1() {
    global $wp_admin_bar;

    if ( current_user_can('edit_theme_options') ) {
        return null;
    }
    $wp_admin_bar->add_menu( array(
        'parent' => 'design',
        'id' => 'themes',
        'title' => __('Themes'),
        'href' => admin_url('themes.php')
    ) );

    $wp_admin_bar->add_menu(
        array(
            'id' => 'design',
            'title' => __('Theme'),
            'href' => admin_url('theme-editor.php')
        )
    );

    $wp_admin_bar->add_menu( array(
        'parent' => 'design',
        'id' => 'editor',
        'title' => __('Editor'),
        'href' => admin_url('theme-editor.php')
    ) );


    if ( current_theme_supports( 'widgets' )  ) {
        $wp_admin_bar->add_menu( array(
            'parent' => 'design',
            'id' => 'widgets',
            'title' => __('Widgets'),
            'href' => admin_url('widgets.php')
        ) );
    }

    if ( current_theme_supports( 'menus' ) ) {
        $wp_admin_bar->add_menu( array(
            'parent' => 'design',
            'id' => 'menus',
            'title' => __('Menus'),
            'href' => admin_url('nav-menus.php')
        ) );
    }
}

add_action( 'admin_bar_menu', 'add_admin_bar_menu1', 80 );
 **/