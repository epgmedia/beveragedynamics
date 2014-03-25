<?php
/*
Plugin Name: Most Popular Stories
Description: Display Most Popular Stories
License: GPL
*/

class epg_most_popular_stories extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'epg_most_popular_stories', 'description' => 'EPG Most Popular' );
        $this->WP_Widget('epg_most_popular_stories', 'Most Popular Stories Widget', $widget_ops);
    }

    function form($instance) {

        $title = isset($instance['title']) ? esc_attr( $instance['title'] ) : '';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo $title; ?>" placeholder="Title" /></p>
        <?php
        }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];

        return $instance;
    }

    function widget($args, $instance) {

        extract($args, EXTR_SKIP);
        global $post;

        $post_type = 'category';

        if ( is_single() ) {
            $post_type = "post";
        }

        echo $before_widget;

        switch($post_type):

            case "post":

                $args = array();

                $args['header'] = 'Most Popular Stories';
                $args['header_start'] = '<h3 class=widget-title>';
                $args['header_end'] = '</h3>';
                $args['limit']  = '7';
                $args['range']  = 'monthly';
                $args['cat']    = $wpp_cat_id;
                $args['post_type'] = 'post';
                $args['stats_comments'] = '0';
                $args['stats_category'] = '1';
                $args['post_html'] = '<li>' .
                    '<div class=most-popular-meta><span class=wpp-category>{category}</span></div>' .
                    '<a href={url} class=wpp-post-title>{text_title}</a>' .
                    '</li>';

                $variables = '';
                foreach ($args as $key => $val) {
                    $variables .= ' ' . $key . '="' . $val . '"';
                }

                echo do_shortcode('[wpp ' . $variables . ']');

                echo $after_widget;

                break;

            case "category":
                $category = get_category( get_query_var( 'cat' ) );
                $wpp_cats = get_categories(array( 'child_of' => $category->cat_ID ));

                $wpp_cat_id = array($category->cat_ID);
                foreach ($wpp_cats as $cat) {
                    $wpp_cat_id[] = $cat->cat_ID;
                }
                $wpp_cat_id = implode(', ', $wpp_cat_id);

                $args = array();

                $args['header'] = 'Most Popular - ' . $category->name;
                $args['header_start'] = '<h3 class=widget-title>';
                $args['header_end'] = '</h3>';
                $args['limit']  = '7';
                $args['range']  = 'monthly';
                $args['cat']    = $wpp_cat_id;
                $args['post_type'] = 'post';
                $args['stats_comments'] = '0';
                $args['stats_category'] = '1';
                $args['post_html'] = '<li>' .
                    '<div class=most-popular-meta><span class=wpp-category>{category}</span></div>' .
                    '<a href={url} class=wpp-post-title>{text_title}</a>' .
                    '</li>';

                $variables = '';
                foreach ($args as $key => $val) {
                    $variables .= ' ' . $key . '="' . $val . '"';
                }

                echo do_shortcode('[wpp ' . $variables . ']');

                echo $after_widget;

                break;

        endswitch;
    }

}