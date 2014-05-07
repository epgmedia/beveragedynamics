<?php
// unregister a bunch of worthless widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Search');
}

/**
 *
 * Displays stat in footer
 *
 * @param bool $visible
 */
function performance( $visible = false ) {

    $stat = sprintf(  '%d queries in %.3f seconds, using %.2fMB memory',
        get_num_queries(),
        timer_stop( 0, 3 ),
        memory_get_peak_usage() / 1024 / 1024
    );

    echo $visible ? $stat : "<!-- {$stat} -->" ;
}

add_action( 'wp_footer', 'performance', 20 );

/****** Add Thumbnails in Manage Posts/Pages List ******/
if ( !function_exists('AddThumbColumn') && function_exists('add_theme_support') ) {

    // for post and page
    add_theme_support('post-thumbnails', array( 'post', 'page' ) );

    function AddThumbColumn($cols) {

        $cols['thumbnail'] = __('Featured Image');

        return $cols;
    }

    function AddThumbValue($column_name, $post_id) {

        $width = (int) 35;
        $height = (int) 35;

        if ( 'thumbnail' == $column_name ) {
            // thumbnail of WP 2.9
            $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
            // image from gallery
            $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
            if ($thumbnail_id)
                $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
            elseif ($attachments) {
                foreach ( $attachments as $attachment_id => $attachment ) {
                    $thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
                }
            }
            if ( isset($thumb) && $thumb ) {
                echo $thumb;
            } else {
                echo __('None');
            }
        }
    }

    // for posts
    add_filter( 'manage_posts_columns', 'AddThumbColumn' );
    add_action( 'manage_posts_custom_column', 'AddThumbValue', 10, 2 );

    // for pages
    add_filter( 'manage_pages_columns', 'AddThumbColumn' );
    add_action( 'manage_pages_custom_column', 'AddThumbValue', 10, 2 );
}

/**
 * White label the admin login
 */
/**
 * Replaces the login header logo URL
 *
 * @param $url
 */
function namespace_login_headerurl( $url ) {
    $url = home_url( '/' );
    return $url;
}
/**
 * Replaces the login header logo title
 *
 * @param $title
 */
function namespace_login_headertitle( $title ) {
    $title = get_bloginfo( 'name' );
    return $title;
}
function namespace_login_style() {
    $imageUrl = get_site_url() . '/wp-content/uploads/2014/02/BeverageDynamicsLogo.png';

    echo <<< STYLESHEET
<style>
    .login h1 a {
        background-image: url( $imageUrl ) !important;
        width: 320px !important;
        height: 59px !important;
        background-size: 320px 59px !important;
    }
</style>
STYLESHEET;
}

/**
 * Remove some unneeded menu bar items
 */
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_menu('comments');
}
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

// Set max number of post revisions to hold
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);
/**
 *
 * Register all actions
 *
 */
// Get rid of crappy widgets
add_action('widgets_init', 'unregister_default_wp_widgets', 1);
// While labeling admin area
add_filter( 'login_headerurl', 'namespace_login_headerurl' );
add_filter( 'login_headertitle', 'namespace_login_headertitle' );
add_action( 'login_head', 'namespace_login_style' );
// Get rid of crappy menu bar links
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
// Add EPG Media menu links
add_action('admin_bar_menu', 'add_admin_bar_link', 50);
