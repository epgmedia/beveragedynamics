<?php
/**
 * Ad Position Widget
 * Adds a widget that takes an ad position's variables and creates a new ad position
 *
 */
class epg_related_stories_widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'epg_related_stories_widget', __("Display stories related to the post", 'reactor') );
        $this->WP_Widget('epg_related_stories_widget', 'Related Stories', $widget_ops);
    }

    function form( $instance ) {
        $title = isset($instance['title']) ? esc_attr( $instance['title'] ) : '';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                type="text" value="<?php echo $title; ?>" placeholder="Widget Title" /></p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);
        global $post;
        $categories = get_the_category($post->ID);

        if ($categories) {
            $category_ids = array();
            foreach ($categories as $individual_category) {
                $category_ids[] = $individual_category->term_id;
            }
            $qargs=array(
                'category__in' => $category_ids,
                'post__not_in' => array($post->ID),
                'posts_per_page'=> 5, // Number of related posts that will be shown.
                'ignore_sticky_posts'=>1
            );
            $my_query = new wp_query( $qargs );

            if( $my_query->have_posts() ) {
                echo $before_widget;
                //echo '<h3>' . $instance['title'] . '</h3>';
                echo $before_title . $instance['title'] . $after_title;
                echo '<ul id="related_posts">';
                while ( $my_query->have_posts() ) {
                    $my_query->the_post(); ?>
                    <li class="relatedcontent"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                <?php
                }
                echo '</ul>';
                echo $after_widget;
            }
        }
        wp_reset_query();
    }
};