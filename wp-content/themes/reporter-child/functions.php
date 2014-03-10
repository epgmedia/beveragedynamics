<?php

function the_category_filter($thelist,$separator=' ') {
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
 * Adds a new ad position
 * Adds a widget that takes an ad position's variables and creates a new widget
 *
 * @link http://wordpress.org/support/topic/featured-posts-widget-with-category-exclude
 */
class WP_Widget_google_ads_position extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_google_ad_position', 'description' => __("Add a new ad position to the site", 'reactor') );
        parent::__construct('dbc-ad-position', __('Double Click Ad Position', 'reactor'), $widget_ops);
        $this->alt_option_name = 'widget_dbc_ad_position';
    }

    function widget($args, $instance) {
        ob_start();
        extract($args);


        if (1) : // has ad code
            ?>
            <?php echo $before_widget; ?>
            <div>

            </div>
            <?php echo $after_widget; ?>
        <?php

        endif; // end has ad code

        $cache[$args['widget_id']] = ob_get_flush();
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['category'] = strip_tags( $new_instance['category'] );
        $this->flush_widget_cache();

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_featured_entries']) )
            delete_option('widget_featured_entries');

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_featured_posts', 'widget');
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $category = isset($instance['category']) ? esc_attr( $instance['category'] ) : '';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts:', 'engine'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <p>
            <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Category:', 'engine'); ?></label>

            <?php

            $args = array(
                'selected' => $category,
                'name' => $this->get_field_name('category'),
                'id' => $this->get_field_id('category'),
                'class' => 'widefat'
            );

            wp_dropdown_categories($args);

            ?>

        </p>
    <?php
    }
}

function WP_Widget_google_ads_position_init() {
    register_widget('WP_Widget_google_ads_position');
}

add_action('widgets_init', 'WP_Widget_google_ads_position_init');