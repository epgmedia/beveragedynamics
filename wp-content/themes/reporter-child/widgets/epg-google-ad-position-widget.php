<?php

/**
 * Ad Position Widget
 * Adds a widget that takes an ad position's variables and creates a new ad position
 *
 */
class epg_google_ad_position_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_google_ad_position', 'description' => __("A simple DoubleClick ad position.", 'reactor') );
        parent::__construct('dbc-ad-position', __('Double Click Ad Position', 'reactor'), $widget_ops);
        $this->alt_option_name = 'widget_dbc_ad_position';
    }

    function form( $instance ) {
        // Type
        $title = isset($instance['title']) ? esc_attr( $instance['title'] ) : '';

        $ad_positions = array(
            'Leaderboard',
            'Box'
        );
        $locations = array(
            'Top',
            'Middle',
            'Bottom'
        );
        $blank = '<option value="BLANK">Select one...</option>';

        $position_options = array();
        $position_options[] = $blank;
        foreach ($ad_positions as $ad) {
            $selected = $ad === $instance['position'] ? ' selected="selected"' : '';
            $position_options[] = '<option value="' . $ad . '"' . $selected . '>' . $ad . '</option>';
        }

        $location_options = array();
        $location_options[] = $blank;
        foreach ($locations as $loc) {
            $selected = $loc === $instance['location'] ? ' selected="selected"' : '';
            $location_options[] = '<option value="' . $loc . '"' . $selected . '>' . $loc . '</option>';
        }

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                type="text" value="<?php echo $title; ?>" placeholder="Widget Title (unused)" /></p>
        <p><label for="<?php echo $this->get_field_id('position'); ?>"><?php _e('Position:', 'engine'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('position'); ?>" name="<?php echo $this->get_field_name('position'); ?>">
                <?php echo implode('', $position_options); ?>
            </select>
        </p>
        <p><label for="<?php echo $this->get_field_id('location'); ?>"><?php _e('Location:', 'engine'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>">
                <?php echo implode('', $location_options); ?>
            </select>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['position'] = $new_instance['position'];
        $instance['location'] = $new_instance['location'];

        return $instance;
    }

    function widget($args, $instance) {

        extract($args);
        extract($instance);
        echo $before_widget;
        the_ad_position($position, $location, $inline = FALSE);
        echo $after_widget;

    }
}