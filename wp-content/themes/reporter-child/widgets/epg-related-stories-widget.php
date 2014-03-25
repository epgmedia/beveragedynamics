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

    function get_epg_related_data($post_id, $instance) {
        global $post, $wpdb;

        // Set post ID for transient
        if ( isset($post_id) ) {
            $post_id = intval( $post_id);
        } elseif (!isset($post_id) && $post->ID) {
            $post_id = $post->ID;
        } elseif( !$post_id ) {
            return false;
        }

        $AND = "AND";
        $defaults = array(
            'taxonomy' => 'post_tag',
            'max' => 5
        );
        $categoryquery = array(
            'taxonomy' => 'category'
        );

        $transient_name = 'epg-related-' . $instance['title'] . '-' . $post_id;
        if( isset($_GET['flush-related-links']) && is_user_logged_in() ) {
            echo '<p>Related links flushed! (' . $transient_name . ')</p>';
            delete_transient( $transient_name );
        }
        $output = get_transient( $transient_name );
        if( $output !== false && !is_preview() && is_user_logged_in() ) {
            //echo $transient_name . ' read!';
            return $output;
        }
        $output = $this->iterate_terms($post_id, $defaults, $AND );
        if (count($output) < $defaults['max']) {
            $cat_output = $this->iterate_terms($post_id, $categoryquery, $AND );
            foreach ($cat_output as $cat) {
                $cat = intval( $cat);
                $output[] = $cat;
            }
        }
        if (count($output) < $defaults['max']) {
            $cat_output = $this->iterate_terms($post_id, $defaults);
            foreach ($cat_output as $cat) {
                $cat = intval( $cat);
                $output[] = $cat;
            }
        }
        if (count($output) < $defaults['max']) {
            $cat_output = $this->iterate_terms($post_id, $categoryquery);
            foreach ($cat_output as $cat) {
                $cat = intval( $cat);
                $output[] = $cat;
            }
        }

        $output = array_slice($output, 0, 5);

        if( !is_preview() ) {
            //echo $transient_name . ' set!';
            set_transient( $transient_name, $output, 24 * HOUR_IN_SECONDS );
        }

        return $output;
    }

    function widget($args, $instance) {
        extract($args);
        global $post, $wpdb;
        if ( !is_single($post->ID) ) {
            return false;
        }
        $post_ids = $this->get_epg_related_data($post->ID, $instance);
        if( !$post_ids ) {
            return false;
        }
        $defaults = array(
            'post__in' => $post_ids,
            //'orderby' => 'post__in',
            'post_type' => array('post'),
            'posts_per_page' => min( array(count($post_ids), 10)),
            'related_title' => 'Related Posts'
        );
        $options = wp_parse_args( $instance, $defaults );
        $related_posts = new WP_Query( $options );
        if( $related_posts->have_posts() ):
            echo $before_widget;
            echo $before_title;
            echo $instance['title'];
            echo $after_title;
                ?>
                <ul id="related-material">
                    <?php while ( $related_posts->have_posts() ):
                        $related_posts->the_post();
                        ?>
                        <li>
                            <a class="related-story" href="<?php the_permalink(); ?>">
                                <div class="related-stories-meta">
                                    <?php
                                    $post_terms = wp_get_object_terms($related_posts->post->ID, 'category');
                                    $term = 'News';
                                    $term_slug = '';
                                    if( isset($post_terms[0]) ) {
                                        $term = $post_terms[0]->name;
                                        $term_slug =  $post_terms[0]->slug;
                                    }
                                    ?>
                                    <span class="<?php echo $term_slug; ?> right-separator"><?php echo $term; ?></span>
                                    &nbsp;<span class="date"><?php the_time('M j, Y'); ?></span>
                                </div>
                                <h5><?php the_title(); ?></h5>
                            </a>
                        </li>
                    <?php endwhile;
                    wp_reset_postdata();
                    ?>
                </ul>
                <?php
            echo $after_widget;
        endif;
    }
};