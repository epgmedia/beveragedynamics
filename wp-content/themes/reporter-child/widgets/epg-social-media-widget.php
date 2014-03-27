<?php

/**
 * EPG Social Media Widget
 * A new widget for social media profiles. Yeah. I know.
 */
class epg_social_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'epg_social_widget', 'description' => __("Links to your social media pages", 'reactor') );
        parent::__construct('epg_social_widget', __('Social Media Icons', 'reactor'), $widget_ops);
        $this->alt_option_name = 'epg_social_media_widget';
    }

    function form( $instance ) {

        $defaults = array(
            'title' => '',
            'target' => 'blank',
            'Facebook' => '',
            'GooglePlus' => '',
            'LinkedIn' => '',
            'Twitter' => '',
            'YouTube' => '',
            'Instagram' => '',
            'Pinterest' => '',
            'RSS' => '',
            'Digg' => ''

        );

        $instance = wp_parse_args($instance, $defaults);

        $social_media_links = array(
            'Facebook' => '',
            'GooglePlus' => '',
            'LinkedIn' => '',
            'Twitter' => '',
            'YouTube' => '',
            'Instagram' => '',
            'Pinterest' => '',
            'RSS' => '',
            'Digg' => ''
        );

        $social_media_urls = array();

        foreach ($social_media_links as $key => $val) {

            $input = array();
            $label              = '<label for="' . $this->get_field_id($key) . '">' . $key .  '';

            $key = strtolower($key);
            $input['class']     = 'class="widefat"';
            $input['id']        = 'id="' . $this->get_field_id($key) . '"';
            $input['name']      = 'name="' . $this->get_field_name($key) . '"';
            $input['type']      = 'type="text"';
            $input['value']     = 'value="' . $instance[$key] . '"';
            $input['holder']    = 'placeholder="' . $instance[$key] . '"';

            $input_item = '<input ' . implode(" ", $input) . ' />';

            $social_media_urls[] = '<p>' . $label . $input_item . '</label>' . '</p>';
        }

        $target_options = array(
            'blank',
            'self',
            'parent',
            'top'
        );

        $targets = array();
        $targets[] = '<option value="" disabled>Select one...</option>';
        foreach ($target_options as $tar) {
            $selected = $tar === $instance['target'] ? ' selected="selected"' : '';
            $targets[] = '<option value="' . $tar .'"' . $selected . '>' . $tar. '</option>';
        }


        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('title:', 'engine'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                type="text" value="<?php echo $instance['title']; ?>" placeholder="Widget Headline" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('target'); ?>">
                <?php _e('Target:', 'engine'); ?>
                <select id="<?php echo $this->get_field_id('target'); ?>" class="widefat" name="<?php echo $this->get_field_name('target'); ?>">
                    <?php echo implode('', $targets); ?>
                </select>
            </label>
        </p>

        <?php
        echo implode("", $social_media_urls);
    }

    function update( $new_instance, $old_instance ) {

        $instance = $new_instance;

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);
        extract($instance);

        $social_media = array_slice($instance, 2);

        $icons = array();
        foreach ( $social_media as $key => $value ) {

            if ($value !== '') {
                $item = '<li class="epg-social-media-links epg-' . $key . '-icon">';
                $item .= '<a class="epg-social-icon-' . $key . '" ';
                $item .= 'title="' . $key . '"';
                $item .= 'href="' . $value . '" target="_' . $target . '">';
                $item .= '<div class="epg-social-box"></div></a></li>';

                $icons[] = $item;
            }

        }

        $images_start = '<ul>';
        $images_end = '</ul>';

        $widget = $before_widget .
                $before_title .
                    $title .
                $after_title .
                $images_start .
                    implode("", $icons) .
                $images_end .
            $after_widget;

        echo $widget;
    }
}